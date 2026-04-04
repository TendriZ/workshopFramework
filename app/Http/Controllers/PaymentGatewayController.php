<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentGatewayController extends Controller
{
    public function customerPage()
    {
        return view('payment-gateway.customer');
    }

    public function listVendor()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Data vendor berhasil diambil',
            'data' => Vendor::orderBy('nama_vendor')->get(),
        ]);
    }

    public function listMenuByVendor($idvendor)
    {
        $menus = Menu::where('idvendor', $idvendor)->orderBy('nama_menu')->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Data menu berhasil diambil',
            'data' => $menus,
        ]);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'idvendor' => 'required|exists:vendor,idvendor',
            'items' => 'required|array|min:1',
            'items.*.idmenu' => 'required|exists:menu,idmenu',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $guest = $this->createGuestUser();
            $total = 0;

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['idmenu']);
                $total += $menu->harga * (int) $item['jumlah'];
            }

            $pesanan = Pesanan::create([
                'idvendor' => $request->idvendor,
                'id_customer' => $guest->id,
                'nama' => $guest->name,
                'total' => $total,
                'status_bayar' => 0,
            ]);

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['idmenu']);
                DetailPesanan::create([
                    'idmenu' => $menu->idmenu,
                    'idpesanan' => $pesanan->idpesanan,
                    'jumlah' => (int) $item['jumlah'],
                    'harga' => $menu->harga,
                    'subtotal' => $menu->harga * (int) $item['jumlah'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

            $this->configureMidtrans();
            $midtransOrderId = 'PG-' . $pesanan->idpesanan . '-' . time();

            $itemDetails = [];
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['idmenu']);
                $itemDetails[] = [
                    'id' => (string) $menu->idmenu,
                    'price' => (int) $menu->harga,
                    'quantity' => (int) $item['jumlah'],
                    'name' => $menu->nama_menu,
                ];
            }

            $snapPayload = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int) $total,
                ],
                'customer_details' => [
                    'first_name' => $guest->name,
                    'email' => $guest->email,
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($snapPayload);

            $pesanan->order_id_midtrans = $midtransOrderId;
            $pesanan->snap_token = $snapToken;
            $pesanan->midtrans_response = [
                'snap_request' => $snapPayload,
            ];
            $pesanan->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'idpesanan' => $pesanan->idpesanan,
                    'nama_customer' => $guest->name,
                    'total' => $pesanan->total,
                    'status_bayar' => $pesanan->status_bayar,
                    'order_id_midtrans' => $pesanan->order_id_midtrans,
                    'snap_token' => $pesanan->snap_token,
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function payOrder(Request $request, $idpesanan)
    {
        $pesanan = Pesanan::findOrFail($idpesanan);

        if (!$pesanan->order_id_midtrans) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'message' => 'Pesanan ini belum memiliki transaksi Midtrans.',
                'data' => [],
            ], 422);
        }

        $this->configureMidtrans();
        $status = Transaction::status($pesanan->order_id_midtrans);
        $this->applyMidtransStatus($pesanan, (array) $status);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Status pembayaran berhasil diverifikasi.',
            'data' => [
                'idpesanan' => $pesanan->idpesanan,
                'order_id_midtrans' => $pesanan->order_id_midtrans,
                'metode_bayar' => $pesanan->metode_bayar,
                'status_bayar' => $pesanan->status_bayar,
            ],
        ]);
    }

    public function midtransNotification(Request $request)
    {
        $payload = $request->all();

        $signature = hash('sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            config('midtrans.server_key')
        );

        if (($payload['signature_key'] ?? '') !== $signature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pesanan = Pesanan::where('order_id_midtrans', $payload['order_id'] ?? '')->first();
        if (!$pesanan) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $this->applyMidtransStatus($pesanan, $payload);

        return response()->json(['message' => 'OK']);
    }

    public function vendorMenuIndex()
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();
        $menus = Menu::with('vendor')->orderBy('idmenu', 'desc')->get();

        return view('payment-gateway.vendor-menu', compact('vendors', 'menus'));
    }

    public function vendorMenuStore(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:1',
            'idvendor' => 'required|exists:vendor,idvendor',
            'path_gambar' => 'nullable|string|max:255',
        ]);

        Menu::create($request->only(['nama_menu', 'harga', 'idvendor', 'path_gambar']));

        return redirect()->route('pg.vendor.menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function vendorMenuUpdate(Request $request, $idmenu)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:1',
            'idvendor' => 'required|exists:vendor,idvendor',
            'path_gambar' => 'nullable|string|max:255',
        ]);

        $menu = Menu::findOrFail($idmenu);
        $menu->update($request->only(['nama_menu', 'harga', 'idvendor', 'path_gambar']));

        return redirect()->route('pg.vendor.menu')->with('success', 'Menu berhasil diupdate.');
    }

    public function vendorMenuDestroy($idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $menu->delete();

        return redirect()->route('pg.vendor.menu')->with('success', 'Menu berhasil dihapus.');
    }

    public function paidOrders()
    {
        $orders = Pesanan::with(['vendor', 'detailPesanans.menu'])
            ->where('status_bayar', 1)
            ->orderBy('idpesanan', 'desc')
            ->get();

        return view('payment-gateway.vendor-paid-orders', compact('orders'));
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$clientKey = config('midtrans.client_key');
        MidtransConfig::$isProduction = (bool) config('midtrans.is_production');
        MidtransConfig::$isSanitized = (bool) config('midtrans.is_sanitized');
        MidtransConfig::$is3ds = (bool) config('midtrans.is_3ds');
    }

    private function applyMidtransStatus(Pesanan $pesanan, array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        $isPaid = false;
        if ($transactionStatus === 'settlement') {
            $isPaid = true;
        }

        if ($transactionStatus === 'capture' && ($fraudStatus === null || $fraudStatus === 'accept')) {
            $isPaid = true;
        }

        if ($paymentType === 'bank_transfer') {
            $pesanan->metode_bayar = 'virtual_account';
        } elseif ($paymentType === 'qris') {
            $pesanan->metode_bayar = 'qris';
        } elseif ($paymentType) {
            $pesanan->metode_bayar = $paymentType;
        }

        $pesanan->status_bayar = $isPaid ? 1 : 0;
        $pesanan->midtrans_response = $payload;
        $pesanan->save();
    }

    private function createGuestUser(): User
    {
        $latestGuest = User::where('name', 'like', 'Guest_%')
            ->select('name')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = 0;
        if ($latestGuest) {
            $parts = explode('_', $latestGuest->name);
            $lastNumber = isset($parts[1]) ? (int) $parts[1] : 0;
        }

        $nextNumber = $lastNumber + 1;
        $guestName = 'Guest_' . str_pad((string) $nextNumber, 7, '0', STR_PAD_LEFT);
        $guestEmail = strtolower($guestName) . '@guest.local';

        while (User::where('email', $guestEmail)->exists()) {
            $nextNumber++;
            $guestName = 'Guest_' . str_pad((string) $nextNumber, 7, '0', STR_PAD_LEFT);
            $guestEmail = strtolower($guestName) . '@guest.local';
        }

        return User::create([
            'name' => $guestName,
            'email' => $guestEmail,
            'password' => Hash::make(Str::random(20)),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
    }
}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tag Harga</title>
    <style>
        /*
         * TnJ No. 108 Label Paper
         * Ukuran kertas: 215mm x 305mm
         * Grid: 5 kolom x 8 baris = 40 label per lembar
         * Available: 201mm x 283mm (after margins)
         * Per label: ~40.2mm x 35.3mm
         */
        @page {
            size: 215mm 305mm;
            margin: 11mm 7mm 11mm 7mm;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        .label-grid {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .label-grid td {
            width: 20%;
            height: 35mm;
            border: 0.5pt dashed #aaa;
            padding: 2mm 2mm;
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
        }

        .label-nama {
            font-size: 8pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 1mm;
            line-height: 1.2;
            word-wrap: break-word;
            overflow: hidden;
        }

        .label-harga {
            font-size: 12pt;
            font-weight: bold;
            color: #000;
            line-height: 1;
        }

        .label-harga .rp {
            font-size: 8pt;
            font-weight: normal;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @php
        $cols = 5;
        $rows = 8;
        $labelsPerPage = $cols * $rows;

        // Calculate how many cells to skip on page 1 (0-indexed)
        $skipCount = ($startY - 1) * $cols + ($startX - 1);

        // Total cells needed across all pages
        $totalItems = count($barangs);
        $totalCells = $skipCount + $totalItems;
        $totalPages = max(1, ceil($totalCells / $labelsPerPage));

        $itemIndex = 0;
    @endphp

    @for($page = 0; $page < $totalPages; $page++)
        <table class="label-grid">
            @for($row = 0; $row < $rows; $row++)
                <tr>
                    @for($col = 0; $col < $cols; $col++)
                        @php
                            $cellIndex = $page * $labelsPerPage + $row * $cols + $col;
                        @endphp
                        <td>
                            @if($cellIndex >= $skipCount && $itemIndex < $totalItems)
                                @php $item = $barangs[$itemIndex]; $itemIndex++; @endphp
                                <div class="label-nama">{{ $item->nama }}</div>
                                <div class="label-harga">
                                    <span class="rp">Rp</span>
                                    {{ number_format($item->harga, 0, ',', '.') }}
                                </div>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </table>

        @if($page < $totalPages - 1)
            <div class="page-break"></div>
        @endif
    @endfor
</body>
</html>

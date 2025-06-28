<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Pengiriman - {{ $pengiriman->no_resi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            height: 40px;
        }
        .barcode {
            height: 80px;
        }
        .section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            width: 160px;
            font-weight: bold;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-box {
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 5px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-item-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 3px;
        }
        .info-item-value {
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background-color: #f9fafb;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            padding: 8px 10px;
        }
        table td {
            padding: 8px 10px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 1cm;
            }
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-right: 10px;
        }
        .button:hover {
            background-color: #43a047;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print" style="margin-bottom: 20px;">
            <button onclick="window.print()" class="button">Print</button>
            <a href="{{ route('pengiriman.show', ['id' => $pengiriman->id]) }}" class="button" style="background-color: #607d8b;">Kembali</a>
        </div>
        
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/csmcargo.png') }}" alt="Manaje">
                <span style="margin-left: 5px; font-weight: bold;">®</span>
            </div>
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($pengiriman->no_resi, 'C128', 3, 80) }}" alt="Barcode" class="barcode">
        </div>
        
        <div class="section">
            <div class="info-row">
                <div class="info-label">Nomor Resi</div>
                <div>: {{ $pengiriman->no_resi }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pengiriman</div>
                <div>: {{ $pengiriman->created_at->format('d M, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Asal</div>
                <div>: {{ $pengiriman->asal }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tujuan</div>
                <div>: {{ $pengiriman->tujuan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div>: {{ $pengiriman->status == 'draft' ? 'Draf (Branch sesuai alamat Semarang)' : $pengiriman->status }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipe</div>
                <div>: {{ $pengiriman->opsiPengiriman->tipe_pengiriman ?? 'Diantar' }}</div>
            </div>
        </div>
        
        <div class="info-grid">
            <div class="section">
                <div class="section-title">Informasi Pengirim</div>
                <div class="info-box">
                    <div class="info-item">
                        <div class="info-item-label">Branch</div>
                        <div class="info-item-value">CSM Jakarta</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Tipe Pengiriman</div>
                        <div class="info-item-value">{{ $pengiriman->opsiPengiriman->tipe_pengiriman ?? 'Diantar' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Nama Pengirim</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->nama_pengirim ?? 'Mawar Melati' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Nomor Telepon</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->telepon_pengirim ?? '+628737181082' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Email Pengirim</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->email_pengirim ?? 'mawarmelati@gmail.com' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Alamat Pengirim</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->alamat_pengirim ?? 'Solo' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Informasi Penerima</div>
                <div class="info-box">
                    <div class="info-item">
                        <div class="info-item-label">Nama Penerima</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->nama_penerima ?? 'Putih Abu' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Nomor Telepon</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->telepon_penerima ?? '+6289746197112' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Email Penerima</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->email_penerima ?? 'mawarmelati@gmail.com' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Alamat Penerima</div>
                        <div class="info-item-value">{{ $pengiriman->pengirimPenerima->alamat_penerima ?? 'Semarang' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-grid">
            <div class="section">
                <div class="section-title">Layanan</div>
                <div class="info-box">
                    <div class="info-item">
                        <div class="info-item-label">Jenis Layanan</div>
                        <div class="info-item-value">{{ $pengiriman->opsiPengiriman->jenis_layanan ?? 'Layanan Instan' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Asuransi Pengiriman</div>
                        <div class="info-item-value">{{ $pengiriman->opsiPengiriman->asuransi ? 'Ya' : '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Packing Pengiriman</div>
                        <div class="info-item-value">{{ $pengiriman->opsiPengiriman->packing_tambahan ? 'Ya' : '-' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Pembayaran</div>
                <div class="info-box">
                    <div class="info-item">
                        <div class="info-item-label">Metode Pembayaran</div>
                        <div class="info-item-value">{{ $pengiriman->informasiPembayaran->metode_pembayaran ?? 'Cash' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Diskon</div>
                        <div class="info-item-value">20%</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Total Sub Biaya</div>
                        <div class="info-item-value">{{ number_format($pengiriman->informasiPembayaran->total_sub_biaya ?? 69000, 0, ',', '.') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">Total Biaya Pengiriman</div>
                        <div class="info-item-value">{{ number_format($pengiriman->informasiPembayaran->total_biaya_pengiriman ?? 45000, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section" style="margin-top: 20px;">
            <table>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA BARANG</th>
                        <th>JENIS BARANG</th>
                        <th>DESKRIPSI BARANG</th>
                        <th>BERAT (KG)</th>
                        <th>PANJANG (CM)</th>
                        <th>LEBAR (CM)</th>
                        <th>TINGGI (CM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengiriman->barangPengiriman as $index => $barang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->jenis_barang }}</td>
                            <td>{{ $barang->deskripsi_barang }}</td>
                            <td>{{ $barang->berat_barang }}</td>
                            <td>{{ $barang->panjang_barang }}</td>
                            <td>{{ $barang->lebar_barang }}</td>
                            <td>{{ $barang->tinggi_barang }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>1</td>
                            <td>Baju</td>
                            <td>Pakaian</td>
                            <td>Baju branded</td>
                            <td>0.2</td>
                            <td>60</td>
                            <td>46</td>
                            <td>70</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Celana</td>
                            <td>Pakaian</td>
                            <td>Celana mahal</td>
                            <td>0.5</td>
                            <td>130</td>
                            <td>46</td>
                            <td>160</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            // Small delay to ensure everything is rendered
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
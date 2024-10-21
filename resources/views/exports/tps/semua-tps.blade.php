<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>KABUPATEN/KOTA</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>KECAMATAN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>DESA/KELURAHAN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>NOMOR TPS</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>ALAMAT</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($tps as $t)
			<tr>
				<td style="font-size: 16px;">{{ strtoupper($t->kelurahan->kecamatan->kabupaten->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($t->kelurahan->kecamatan->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($t->kelurahan->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($t->getThreeDigitsId()) }}</td>
				<td style="font-size: 16px;">-</td>
			</tr>
		@endforeach
	</tbody>
</table>
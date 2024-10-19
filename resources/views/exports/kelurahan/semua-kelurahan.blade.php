<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>KELURAHAN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>KECAMATAN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>KABUPATEN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>PROVINSI</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($kelurahan as $kel)
			<tr>
				<td style="font-size: 16px;">{{ strtoupper($kel->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($kel->kecamatan->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($kel->kecamatan->kabupaten->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($kel->kecamatan->kabupaten->provinsi->nama) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
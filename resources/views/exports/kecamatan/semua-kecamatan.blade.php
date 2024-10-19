<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>KECAMATAN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>KABUPATEN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>PROVINSI</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($kecamatan as $kec)
			<tr>
				<td style="font-size: 16px;">{{ strtoupper($kec->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($kec->kabupaten->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($kec->kabupaten->provinsi->nama) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
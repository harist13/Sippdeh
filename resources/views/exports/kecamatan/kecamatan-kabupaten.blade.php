<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>{{ $kabupaten->provinsi->nama }}</strong></th>
		</tr>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>{{ $kabupaten->nama }}</strong></th>
		</tr>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>KECAMATAN</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($kabupaten->kecamatan as $kec)
			<tr>
				<td style="font-size: 16px;">{{ strtoupper($kec->nama) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
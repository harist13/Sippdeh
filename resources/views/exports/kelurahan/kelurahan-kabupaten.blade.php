<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>{{ $provinsi->nama }}</strong></th>
		</tr>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>{{ $kabupaten->nama }}</strong></th>
		</tr>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>{{ $kecamatan->nama }}</strong></th>
		</tr>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>KELURAHAN</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($kelurahan as $kel)
			<tr>
				<td style="font-size: 16px;">{{ strtoupper($kel->nama) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
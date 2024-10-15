<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th colspan="2" style="font-size: 16px;">{{ $kabupaten->nama }}</th>
		</tr>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>ID</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>KECAMATAN</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($kecamatan as $kec)
			<tr>
				<td style="font-size: 16px;">{{ $kec->getThreeDigitsId() }}</td>
				<td style="font-size: 16px;">{{ $kec->nama }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
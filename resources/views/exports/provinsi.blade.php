<table style="border: 1px solid black;">
	<thead>
		@isset ($kabupaten)
			<tr>
				<th colspan="2" style="font-size: 16px;">{{ $kabupaten->nama }}</th>
			</tr>	
		@endisset
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>PROVINSI</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($provinsi as $prov)
			<tr>
				<td style="font-size: 16px;">{{ $prov->nama }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
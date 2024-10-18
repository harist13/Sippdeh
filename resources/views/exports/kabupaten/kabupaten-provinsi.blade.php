<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th colspan="2" style="font-size: 16px;"><strong>{{ $provinsi->nama }}</strong></th>
		</tr>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>KABUPATEN</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($provinsi->kabupaten as $kab)
			<tr>
				<td style="font-size: 16px;">{{ $kab->nama }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
<table style="border: 1px solid black;">
	<thead>
		<tr>
			<th width="300px" style="font-size: 16px;"><strong>KABUPATEN</strong></th>
			<th width="300px" style="font-size: 16px;"><strong>PROVINSI</strong></th>
		</tr>
	</thead>
	<tbody>
		@foreach($kabupaten as $kab)
			<tr>
				<td style="font-size: 16px;">{{ strtoupper($kab->nama) }}</td>
				<td style="font-size: 16px;">{{ strtoupper($kab->provinsi->nama) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th>Kecamatan</th>
			<th>Kabupaten</th>
		</tr>
	</thead>
	<tbody>
		@foreach($kecamatan as $kec)
			<tr>
				<td>{{ $kec->nama }}</td>
				<td>{{ $kec->kabupaten->nama }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
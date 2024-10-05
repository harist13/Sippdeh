<div id="map" style="width: 800px;">
  <?xml version="1.0" encoding="utf-8"?>
  <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
    @include('peta-kaltim.regions.berau', ['color' => '#2259A8'])
    @include('peta-kaltim.regions.kutai-timur', ['color' => '#F9D926'])
    @include('peta-kaltim.regions.penajam-paser-utara', ['color' => '#F9D926'])
    @include('peta-kaltim.regions.paser', ['color' => '#F9D926'])
    @include('peta-kaltim.regions.mahakam-ulu', ['color' => '#2259A8'])
    @include('peta-kaltim.regions.kutai-barat', ['color' => '#2259A8'])
    @include('peta-kaltim.regions.kutai-kartanegara', ['color' => '#2259A8'])
    @include('peta-kaltim.regions.samarinda', ['color' => '#2259A8'])
    @include('peta-kaltim.regions.bontang', ['color' => '#2259A8'])
    @include('peta-kaltim.regions.balikpapan', ['color' => '#2259A8'])
  </svg>
</div>

<div id="tooltip" class="tooltip">
  <p>Kutai Kartanegara</p>
  <p>Total Suara Sah       : 70.000 orang</p>
  <p>Total Suara Tidak Sah : 18.000 orang</p>
</div>

<script>
  const groups = document.querySelectorAll('#map g.region')

  groups.forEach(group => {
    group.addEventListener('mouseover', event => onMouseOver({ event, groups, group }))
    group.addEventListener('mouseout', event => onMouseOut({ event, groups, group }))
  })

  function onMouseOver({ event, groups, group }) {
    const unselectedColor = '#667080'

    groups.forEach(_group => {
      if (_group.id != group.id) {
        const paths = _group.querySelectorAll('path')
        paths.forEach(path => path.style.fill = unselectedColor)
      }
    })

    const tooltip = document.getElementById('tooltip')
    const regionName = group.getAttribute('id')
    // tooltip.textContent = `Region: ${regionName}`
    tooltip.style.display = 'block'
    tooltip.style.left = event.clientX + 10 + 'px'
    tooltip.style.top = event.clientY + 10 + 'px'
  }

  function onMouseOut({ event, groups }) {
    groups.forEach(group => {
      const paths = group.querySelectorAll('path')
      paths.forEach(path => path.style.fill = group.dataset.color)
    })

    tooltip.style.display = 'none'
  }
</script>
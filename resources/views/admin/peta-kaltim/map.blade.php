<div id="map" style="width: 500px;">
  <?xml version="1.0" encoding="utf-8"?>
  <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
    @include('admin.peta-kaltim.regions.berau', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.kutai-timur', ['color' => '#F9D926'])
    @include('admin.peta-kaltim.regions.penajam-paser-utara', ['color' => '#F9D926'])
    @include('admin.peta-kaltim.regions.paser', ['color' => '#F9D926'])
    @include('admin.peta-kaltim.regions.mahakam-ulu', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.kutai-barat', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.kutai-kartanegara', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.samarinda', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.bontang', ['color' => '#2259A8'])
    @include('admin.peta-kaltim.regions.balikpapan', ['color' => '#2259A8'])
  </svg>
</div>

<style>
  .selected-region {
    opacity: 0.8;
  }

  path {
    transition: opacity 0.1s;
  }
</style>

<script>
  const groups = document.querySelectorAll('#map g.region')

  groups.forEach(group => {
    group.addEventListener('mouseover', event => onMouseOver({ event, groups, group }))
    group.addEventListener('mouseout', event => onMouseOut({ event, groups, group }))
  })

  function onMouseOver({ event, groups, group }) {
    const paths = group.querySelectorAll('path')
    paths.forEach(path => path.classList.add('selected-region'))

    const tooltip = document.getElementById('tooltip')
    tooltip.style.left = `${event.clientX}px`
    tooltip.style.top = `${event.clientY}px`
    tooltip.classList.remove('hidden')
  }

  function onMouseOut({ event, groups }) {
    groups.forEach(group => {
      const paths = group.querySelectorAll('path')
      paths.forEach(path => path.classList.remove('selected-region'))
    })

    tooltip.classList.add('hidden')
  }
</script>
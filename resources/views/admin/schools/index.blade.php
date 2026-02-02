@extends('layouts.app')

@section('content')
<div style="max-width: 600px">
  <h1>Iskolák</h1>

  <a href="/admin/schools/create">➕ Új iskola</a>

  <ul id="school-list"></ul>

  <p id="error"></p>
</div>

<script type="module">
  import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

  const supabase = createClient(
    import.meta.env.VITE_SUPABASE_URL,
    import.meta.env.VITE_SUPABASE_ANON_KEY
  )

  async function loadSchools() {
    const { data, error } = await supabase
      .from('schools')
      .select('id, name, created_at')
      .order('created_at', { ascending: false })

    if (error) {
      document.getElementById('error').innerText = error.message
      return
    }

    const list = document.getElementById('school-list')
    list.innerHTML = ''

    data.forEach(school => {
      const li = document.createElement('li')
      li.innerText = school.name
      list.appendChild(li)
    })
  }

  loadSchools()
</script>
@endsection

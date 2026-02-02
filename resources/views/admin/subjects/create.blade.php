@extends('layouts.app')
@section('content')
<div style="max-width: 400px">
  <h1>Tantárgy létrehozása</h1>

  <select id="school-select"></select>
  <input type="text" id="subject-name" placeholder="Tantárgy neve" />
  <button onclick="createSubject()">Mentés</button>
  <p id="result"></p>
</div>

<script type="module">
import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'
const supabase = createClient(import.meta.env.VITE_SUPABASE_URL, import.meta.env.VITE_SUPABASE_ANON_KEY)

async function loadSchools() {
  const { data } = await supabase.from('schools').select('id,name').order('name')
  const select = document.getElementById('school-select')
  data.forEach(s => select.appendChild(new Option(s.name, s.id)))
}
async function createSubject() {
  const school_id = document.getElementById('school-select').value
  const name = document.getElementById('subject-name').value
  const { error } = await supabase.from('subjects').insert({ school_id, name })
  document.getElementById('result').innerText = error ? error.message : 'Tantárgy létrehozva!'
}
loadSchools()
</script>
@endsection

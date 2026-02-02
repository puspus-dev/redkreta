@extends('layouts.app')
@section('content')
<div style="max-width: 500px">
  <h1>Tantárgy hozzárendelés</h1>

  <select id="teacher-select"></select>
  <select id="school-select"></select>
  <select id="class-select"></select>
  <select id="subject-select"></select>

  <button onclick="assignSubject()">Mentés</button>
  <p id="result"></p>
</div>

<script type="module">
import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'
const supabase = createClient(import.meta.env.VITE_SUPABASE_URL, import.meta.env.VITE_SUPABASE_ANON_KEY)

async function loadTeachers() {
  const { data } = await supabase.from('profiles').select('id,name').eq('role','teacher')
  const select = document.getElementById('teacher-select')
  data.forEach(t => select.appendChild(new Option(t.name, t.id)))
}
async function loadSchools() {
  const { data } = await supabase.from('schools').select('id,name')
  const select = document.getElementById('school-select')
  data.forEach(s => select.appendChild(new Option(s.name, s.id)))
  loadClasses()
  loadSubjects()
}
async function loadClasses() {
  const school_id = document.getElementById('school-select').value
  const { data } = await supabase.from('classes').select('id,name').eq('school_id', school_id)
  const select = document.getElementById('class-select')
  select.innerHTML=''
  data.forEach(c => select.appendChild(new Option(c.name, c.id)))
}
async function loadSubjects() {
  const school_id = document.getElementById('school-select').value
  const { data } = await supabase.from('subjects').select('id,name').eq('school_id', school_id)
  const select = document.getElementById('subject-select')
  select.innerHTML=''
  data.forEach(s => select.appendChild(new Option(s.name, s.id)))
}
async function assignSubject() {
  const teacher_id = document.getElementById('teacher-select').value
  const class_id = document.getElementById('class-select').value
  const subject_id = document.getElementById('subject-select').value
  const { error } = await supabase.from('teacher_subjects').insert({teacher_id,class_id,subject_id})
  document.getElementById('result').innerText = error ? error.message : 'Hozzárendelve!'
}
loadTeachers(); loadSchools();
document.getElementById('school-select').addEventListener('change',()=>{
  loadClasses(); loadSubjects();
})
</script>
@endsection

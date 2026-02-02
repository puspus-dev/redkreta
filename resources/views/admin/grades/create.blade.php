@extends('layouts.app')
@section('content')
<div style="max-width: 500px">
  <h1>Jegy felvétele</h1>

  <select id="class-select"></select>
  <select id="subject-select"></select>
  <select id="student-select"></select>
  <input type="number" id="grade-value" min="1" max="5" placeholder="Jegy" />
  <button onclick="addGrade()">Mentés</button>
  <p id="result"></p>
</div>

<script type="module">
import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'
const supabase = createClient(import.meta.env.VITE_SUPABASE_URL, import.meta.env.VITE_SUPABASE_ANON_KEY)

async function loadClasses() {
  const { data } = await supabase.from('teacher_subjects')
    .select('class_id,classes(name)')
    .eq('teacher_id', supabase.auth.user().id)
  const select = document.getElementById('class-select')
  select.innerHTML=''
  data.forEach(c => select.appendChild(new Option(c.classes.name,c.class_id)))
  loadSubjects()
}
async function loadSubjects() {
  const class_id = document.getElementById('class-select').value
  const { data } = await supabase.from('teacher_subjects')
    .select('subject_id,subjects(name)')
    .eq('teacher_id', supabase.auth.user().id)
    .eq('class_id', class_id)
  const select = document.getElementById('subject-select')
  select.innerHTML=''
  data.forEach(s=>select.appendChild(new Option(s.subjects.name,s.subject_id)))
  loadStudents()
}
async function loadStudents() {
  const class_id = document.getElementById('class-select').value
  const { data } = await supabase.from('students').select('id,profiles(name)')
    .eq('class_id',class_id)
  const select = document.getElementById('student-select')
  select.innerHTML=''
  data.forEach(s=>select.appendChild(new Option(s.profiles.name,s.id)))
}
async function addGrade(){
  const student_id = document.getElementById('student-select').value
  const subject_id = document.getElementById('subject-select').value
  const class_id = document.getElementById('class-select').value
  const value = parseInt(document.getElementById('grade-value').value)
  const teacher_id = supabase.auth.user().id

  const { error } = await supabase.from('grades').insert({student_id,subject_id,teacher_id,value})
  document.getElementById('result').innerText = error ? error.message : 'Jegy rögzítve!'
}

document.getElementById('class-select').addEventListener('change',()=>{loadSubjects(); loadStudents();})
loadClasses()
</script>
@endsection

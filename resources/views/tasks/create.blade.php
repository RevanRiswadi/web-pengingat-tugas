<form action="{{ route('tasks.store') }}" method="POST">
    @csrf

    <label>Judul Tugas</label>
    <input type="text" name="judul_tugas" value="{{ old('judul_tugas') }}">
    @error('judul_tugas') <span>{{ $message }}</span> @enderror

    <label>Mata Pelajaran</label>
    <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}">
    @error('mata_pelajaran') <span>{{ $message }}</span> @enderror

    <label>Tenggat Waktu</label>
    <input type="date" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}">
    @error('tenggat_waktu') <span>{{ $message }}</span> @enderror

    <button type="submit">Simpan</button>
</form>
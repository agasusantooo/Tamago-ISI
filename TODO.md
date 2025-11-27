# TODO: Fix Registration Data Not Entering Database

## Current Issue
- User registration only creates User record, but for mahasiswa role, Mahasiswa record is needed.
- Role is determined by email domain (e.g., @student.isi.ac.id for mahasiswa).
- Data should be created automatically based on email.

## Tasks
- [ ] Update RegisterController to determine role from email domain
- [ ] Create corresponding records (Mahasiswa, Dosen, etc.) automatically
- [ ] For mahasiswa: nim = email prefix, nama = name, prodi/angkatan defaults
- [ ] Test registration for different roles

## ✅ COMPLETED: Status Synchronization Between Dospem and Mahasiswa

### Changes Made
- [x] Updated `MahasiswaBimbinganController::approveProposal()` to set mahasiswa status to 'proposal_disetujui' when proposal is approved
- [x] Updated `MahasiswaBimbinganController::rejectProposal()` to set mahasiswa status to 'proposal_ditolak' when proposal is rejected
- [x] Updated `MahasiswaBimbinganController::approveBimbingan()` to set mahasiswa status to 'bimbingan_disetujui' when bimbingan is approved
- [x] Updated `MahasiswaBimbinganController::rejectBimbingan()` to set mahasiswa status to 'bimbingan_ditolak' when bimbingan is rejected
- [x] Added proper imports for Mahasiswa and Dosen models

### Result
Now when dosen pembimbing approves or rejects proposals and bimbingan schedules, the corresponding mahasiswa's status is automatically updated in the database, ensuring both sides stay synchronized.

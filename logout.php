<?php
// ============================================
// LOGOUT.PHP — VERSI SEDERHANA
// Hapus session, lalu langsung balik ke portofolio (index.html)
// ============================================

session_start();
session_unset();   // hapus semua data session
session_destroy(); // hancurkan session-nya

header("Location: index.html");
exit;
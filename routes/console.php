<?php
use Illuminate\Support\Facades\Schedule;
Schedule::call(fn() => \App\Models\Etudiant::all()->each->recalculerMoyenne())->weekly();

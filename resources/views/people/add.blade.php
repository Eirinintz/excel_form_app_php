<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Νέα Εισαγωγή</title>

<style>

/* Container under the input */
#title-suggestions {
    border: 1px solid #ccc;
    max-height: 200px;
    overflow: auto;
    width: 100%;
    background-color: white;
    position: absolute; /* allows it to float over the form */
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    padding: 0;
    margin: 0;
}

/* Individual suggestion boxes */
.suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.suggestion-item:hover {
    background-color: #f0f0f0;
}


#ekdoths-suggestions {
    border: 1px solid #ccc;
    max-height: 200px;
    overflow: auto;
    width: 100%;
    background-color: white;
    position: absolute; /* allows it to float over the form */
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    padding: 0;
    margin: 0;
}

/* Individual suggestion boxes */
.suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.suggestion-item:hover {
    background-color: #f0f0f0;
}


* {
    box-sizing: border-box;
}

body {
    background: #f4f7fb;
    font-family: "Segoe UI", sans-serif;
    margin: 0;
    padding: 0;
}

.page-wrapper {
    max-width: 1400px;
    margin: 30px auto;
    padding: 25px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

h1 {
    margin-bottom: 25px;
    color: #1f3c88;
}

form {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
    width: 100%;
}

.field-box {
    position: relative;
    background: #e8f0ff;
    padding: 10px;
    border-radius: 8px;
    border-left: 4px solid #1f3c88;
}

.field-box label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #1f3c88;
}

input,
textarea,
select {
    width: 100%;
    padding: 6px 8px;
    border-radius: 5px;
    border: 1px solid #c6d4ff;
    font-size: 14px;
    background: #fff;
}

input[readonly] {
    background: #dde6ff;
    font-weight: bold;
}

/* textarea μικρό αρχικά – μεγαλώνει προς τα κάτω */
textarea {
    resize: none;
    overflow: auto;
    min-height: 28px;
    line-height: 1.3;
}

/* ΚΟΥΜΠΙΑ */
.submit-wrapper {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    margin-top: 25px;
    flex-wrap: wrap;
}

button {
    background: #1f3c88;
    color: white;
    border: none;
    padding: 11px 30px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #162c63;
}

.btn-secondary {
    background: #2d6cdf;
    color: white;
    text-decoration: none;
    padding: 11px 30px;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 600;
}

.btn-secondary:hover {
    background: #1f56b3;
}

/* ΜΗΝΥΜΑ ΥΠΟΒΟΛΗΣ */
.submit-msg {
    font-size: 14px;
    color: #1f3c88;
    font-weight: 600;
}
</style>
</head>

<script>
function confirmSubmit() {
    return confirm("Είσαι σίγουρος για αυτήν την υποβολή?");
}

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/autocomplete.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const authorInput = document.getElementById("id_syggrafeas");
    const kohaInput = document.getElementById("id_koha");
    if (!authorInput || !kohaInput) return;

    let manualEdit = false;

    // Αν ο χρήστης επεξεργαστεί το koha, σταματάμε το auto-fill
    kohaInput.addEventListener("input", function () {
        manualEdit = true;
    });

    // Auto-fill KOHA όταν γράφεται το syggrafeas
    authorInput.addEventListener("keyup", function () {
        if (manualEdit) return;
        let value = this.value.trim();
        if (!value.includes(",")) return;

        const parts = value.split(",");
        if (parts.length !== 2) return;

        const surname = parts[0].trim();
        const name = parts[1].trim();
        if (!surname || !name) return;

        kohaInput.value = `${name} ${surname}`;
    });
});
</script>
<body>

@if ($submitted)
<div style="background: #d4edda; padding: 15px; margin-bottom: 20px; border-radius: 5px; border-left: 4px solid #28a745;">
    @if ($all_complete)
        <strong>🎉 Επιτυχία!</strong><br>
        Η εγγραφή αποθηκεύτηκε επιτυχώς! Όλες οι ελλιπείς εγγραφές συμπληρώθηκαν.
        <br><br>
        <a href="{{ route('people.incomplete') }}" style="color: #155724; text-decoration: underline;">
             ← Πίσω στις ελλιπείς εγγραφές
        </a>
    @elseif ($is_editing)
        <strong>✅ Εγγραφή Ενημερώθηκε!</strong><br>
        Η προηγούμενη εγγραφή αποθηκεύτηκε. Συμπληρώστε τα ελλιπή δεδομένα για την επόμενη εγγραφή.
    @else
        <strong>✅ Επιτυχία!</strong><br>
         Η εγγραφή προστέθηκε επιτυχώς!
    @endif
</div>
@endif

@if ($is_editing)
<div style="margin-bottom: 20px;">
    <a href="{{ route('people.incomplete') }}"
       style="background: #6c757d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; display: inline-block;">
        ← Back to Incomplete Records List
    </a>
</div>
@endif


@if ($is_editing && !$submitted)
<div style="background: #fff3cd; padding: 15px; margin-bottom: 20px; border-radius: 5px; border-left: 4px solid #ffc107;">
    <strong>⚠️ Επεξεργασία ελειπή δεδομένων</strong><br>
    Συμπληρώνετε τα ελλιπή δεδομένα για την εγγραφή #{{ $next_number }}
</div>
@endif

<div class="page-wrapper">
    <h1>Νέα Εισαγωγή Βιβλίου</h1>

    <form method="post" action="{{ route('people.add', request()->query()) }}">
        @csrf

        <div class="field-box">
            <label>ΑΡΙΘΜΟΣ ΕΙΣΑΓΩΓΗΣ</label>
            <input type="text" value="{{ $next_number }}" readonly>
        </div>

        <div class="field-box">
            <label>ΗΜΕΡΟΜΗΝΙΑ ΕΙΣΑΓΩΓΗΣ</label>
            <input id="id_hmeromhnia_eis" type="text" name="hmeromhnia_eis" value="{{ old('hmeromhnia_eis', $person?->hmeromhnia_eis ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΣΥΓΓΡΑΦΕΑΣ</label>
            <textarea id="id_syggrafeas" name="syggrafeas" rows="1">{{ old('syggrafeas', $person?->syggrafeas ?? '') }}</textarea>
        </div>

        <div class="field-box">
            <label>ΣΥΓΓΡΑΦΕΑΣ KOHA</label>
            <textarea id="id_koha" name="koha" rows="1">{{ old('koha', $person?->koha ?? '') }}</textarea>
        </div>

        <div class="field-box">
            <label>ΤΙΤΛΟΣ</label>
            <textarea id="id_titlos" name="titlos" rows="1">{{ old('titlos', $person?->titlos ?? '') }}</textarea>
            <div id="title-suggestions" class="autocomplete-box"></div>
        </div>

        <div class="field-box">
            <label>ΕΚΔΟΤΗΣ</label>
            <textarea id="id_ekdoths" name="ekdoths" rows="1">{{ old('ekdoths', $person?->ekdoths ?? '') }}</textarea>
            <div id="ekdoths-suggestions" class="autocomplete-box"></div>
        </div>

        <div class="field-box">
            <label>ΕΚΔΟΣΗ</label>
            <input id="id_ekdosh" type="text" name="ekdosh" value="{{ old('ekdosh', $person?->ekdosh ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΕΤΟΣ ΕΚΔΟΣΗΣ</label>
            <input id="id_etosEkdoshs" type="text" name="etosEkdoshs" value="{{ old('etosEkdoshs', $person?->etosEkdoshs ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΤΟΠΟΣ ΕΚΔΟΣΗΣ</label>
            <input id="id_toposEkdoshs" type="text" name="toposEkdoshs" value="{{ old('toposEkdoshs', $person?->toposEkdoshs ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΣΧΗΜΑ</label>
            <input id="id_sxhma" type="text" name="sxhma" value="{{ old('sxhma', $person?->sxhma ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΣΕΛΙΔΕΣ</label>
            <input id="id_selides" type="text" name="selides" value="{{ old('selides', $person?->selides ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΤΟΜΟΣ</label>
            <input id="id_tomos" type="text" name="tomos" value="{{ old('tomos', $person?->tomos ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΤΡΟΠΟΣ ΠΡΟΜΗΘΕΙΑΣ / ΠΑΡΑΤΗΡΗΣΕΙΣ</label>
            <textarea id="id_troposPromPar" name="troposPromPar" rows="1">{{ old('troposPromPar', $person?->troposPromPar ?? '') }}</textarea>
        </div>

        <div class="field-box">
            <label>ISBN</label>
            <input id="id_ISBN" type="text" name="ISBN" value="{{ old('ISBN', $person?->ISBN ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΣΤΗΛΗ 1</label>
            <input id="id_sthlh1" type="text" name="sthlh1" value="{{ old('sthlh1', $person?->sthlh1 ?? '') }}">
        </div>

        <div class="field-box">
            <label>ΣΤΗΛΗ 2</label>
            <input id="id_sthlh2" type="text" name="sthlh2" value="{{ old('sthlh2', $person?->sthlh2 ?? '') }}">
        </div>

        <div class="submit-wrapper">
            <a href="{{ route('home') }}" class="btn-secondary">Αρχική</a>
            <button type="submit" onclick="return confirmSubmit();">Υποβολή</button>

            @if(request()->query('submitted') == '1')
                <span class="submit-msg" id="submit-msg">Υποβλήθηκε</span>
            @endif
        </div>
    </form>
</div>




</body>
</html>

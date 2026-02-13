<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Edit Record</title>

    <style>
    /* ===== BACKGROUND (ίδιο με την εικόνα) ===== */
    body {
        margin: 0;
        font-family: "Segoe UI", Tahoma, sans-serif;
        background: #e6d9d6;
    }

    /* ===== FORM CONTAINER ===== */
    .form-container {
        max-width: 650px;
        margin: 60px auto;
        background: rgba(255, 255, 255, 0.96);
        padding: 30px 35px;
        border-radius: 14px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    }

    /* ===== TITLE ===== */
    .form-container h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }

    /* ===== FORM GROUP ===== */
    .form-group {
        margin-bottom: 18px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
    }

    input, textarea, select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus,
    textarea:focus,
    select:focus {
        border-color: #1f3c88;
        box-shadow: 0 0 0 2px rgba(31,60,136,0.15);
        outline: none;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    /* ===== ERRORS ===== */
    .error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 4px;
        display: block;
    }

    /* ===== BUTTONS ===== */
    .buttons {
        text-align: center;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 26px;
        margin: 6px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .btn-save {
        background-color: #28a745;
        color: white;
    }

    .btn-cancel {
        background-color: #6c757d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    </style>
</head>

<body>

<div class="form-container">

    <h2>Επεξεργασία Εγγραφής #{{ $person->ari8mosEisagoghs }}</h2>

    <form method="post" action="{{ route('people.edit', ['ari8mos' => $person->ari8mosEisagoghs]) }}"
          onsubmit="return confirm('Είστε σίγουροι ότι θέλετε να αποθηκεύσετε τις αλλαγές;');">
        @csrf

        @php
            $fields = [
                ['name'=>'hmeromhnia_eis','label'=>'ΗΜΕΡΟΜΗΝΙΑ ΕΙΣΑΓΩΓΗΣ','type'=>'text'],
                ['name'=>'syggrafeas','label'=>'ΣΥΓΓΡΑΦΕΑΣ','type'=>'textarea'],
                ['name'=>'koha','label'=>'ΚΟΗΑ','type'=>'textarea'],
                ['name'=>'titlos','label'=>'ΤΙΤΛΟΣ','type'=>'textarea'],
                ['name'=>'ekdoths','label'=>'ΕΚΔΟΤΗΣ','type'=>'textarea'],
                ['name'=>'ekdosh','label'=>'ΕΚΔΟΣΗ','type'=>'text'],
                ['name'=>'etosEkdoshs','label'=>'ΕΤΟΣ ΕΚΔΟΣΗΣ','type'=>'text'],
                ['name'=>'toposEkdoshs','label'=>'ΤΟΠΟΣ ΕΚΔΟΣΗΣ','type'=>'text'],
                ['name'=>'sxhma','label'=>'ΣΧΗΜΑ','type'=>'text'],
                ['name'=>'selides','label'=>'ΣΕΛΙΔΕΣ','type'=>'text'],
                ['name'=>'tomos','label'=>'ΤΟΜΟΣ','type'=>'text'],
                ['name'=>'troposPromPar','label'=>'ΤΡΟΠΟΣ ΠΡΟΜΗΘΕΙΑΣ / ΠΑΡΑΤΗΡΗΣΕΙΣ','type'=>'textarea'],
                ['name'=>'ISBN','label'=>'ISBN','type'=>'text'],
                ['name'=>'sthlh1','label'=>'ΣΤΗΛΗ 1','type'=>'text'],
                ['name'=>'sthlh2','label'=>'ΣΤΗΛΗ 2','type'=>'text'],
            ];
        @endphp

        @foreach($fields as $f)
        <div class="form-group">
            <label for="id_{{ $f['name'] }}">{{ $f['label'] }}</label>

            @if($f['type']==='textarea')
                <textarea id="id_{{ $f['name'] }}" name="{{ $f['name'] }}">{{ old($f['name'], $person->{$f['name']} ?? '') }}</textarea>
            @else
                <input id="id_{{ $f['name'] }}" type="{{ $f['type'] }}" name="{{ $f['name'] }}" value="{{ old($f['name'], $person->{$f['name']} ?? '') }}">
            @endif

            @error($f['name'])
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        @endforeach

        <div class="buttons">
            <button type="submit" class="btn btn-save">Αποθήκευση</button>
            <a href="{{ route('people.index') }}" class="btn btn-cancel">Ακύρωση</a>
        </div>

    </form>

</div>

</body>
</html>

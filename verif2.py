"""
Vérifie uniquement les expressions PHP des vues :
  - le contenu de {{ ... }} et {!! ... !!}
  - les arguments de @include(...), @if(...), @foreach(...)
Chacune est soumise à « php -l » isolément.
"""
import glob, re, subprocess, tempfile, os

def php_valide(expression: str, gabarit: str) -> bool:
    code = "<?php " + gabarit % expression + " ?>"
    with tempfile.NamedTemporaryFile('w', suffix='.php', delete=False) as f:
        f.write(code); tmp = f.name
    r = subprocess.run(['php', '-l', tmp], capture_output=True, text=True)
    os.unlink(tmp)
    return r.returncode == 0

def arguments(source: str, directive: str):
    """Extrait les arguments d'une directive, en comptant les parenthèses."""
    for m in re.finditer(r'@'+directive+r'\s*\(', source):
        i = m.end(); profondeur = 1; guillemet = None
        while i < len(source) and profondeur:
            c = source[i]
            if guillemet:
                if c == '\\': i += 2; continue
                if c == guillemet: guillemet = None
            elif c in '"\'': guillemet = c
            elif c == '(': profondeur += 1
            elif c == ')': profondeur -= 1
            i += 1
        yield source[:m.start()].count('\n') + 1, source[m.end():i-1]

problemes = []
for p in sorted(glob.glob('resources/views/**/*.blade.php', recursive=True)):
    s = re.sub(r'\{\{--.*?--\}\}', '', open(p).read(), flags=re.S)

    # Expressions d'affichage
    for m in re.finditer(r'\{\{(.+?)\}\}|\{!!(.+?)!!\}', s, re.S):
        expr = (m.group(1) or m.group(2)).strip()
        if not php_valide(expr, "$x = %s;"):
            problemes.append((p, s[:m.start()].count('\n') + 1, expr[:80]))

    # Arguments de directives
    gabarits = {
        'include': "f(%s);",
        'if': "if (%s) {}",
        'elseif': "if (%s) {}",
        'unless': "if (%s) {}",
        'foreach': "foreach (%s) {}",
    }

    for directive, gabarit in gabarits.items():
        for ligne, args in arguments(s, directive):
            if not php_valide(args, gabarit):
                problemes.append((p, ligne, f'@{directive}({args[:70]})'))

print(f'{len(problemes)} expression(s) invalide(s)\n')
for p, ligne, extrait in problemes[:25]:
    print(f"  {p.replace('resources/views/','')}:{ligne}")
    print(f"     {extrait}")

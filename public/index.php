<?php
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['consent'])) {
			$name = $_POST['name'];
			$surname = $_POST['surname'];
			$email = $_POST['email'];
			unset($_POST['name']);
			unset($_POST['surname']);
			unset($_POST['email']);
			unset($_POST['consent']);

			$db = new SQLite3(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'corsolinux.sqlite', SQLITE3_OPEN_READWRITE);
			$s = $db->prepare('INSERT INTO People(`Name`, Surname, Email) VALUES (:n, :s, :e)');
			$s->bindValue(':n', $name, SQLITE3_TEXT);
			$s->bindValue(':s', $surname, SQLITE3_TEXT);
			$s->bindValue(':e', $email, SQLITE3_TEXT);
			$result = $s->execute();
			if($result === false) {
				header('Content-Type: text/html; charset=utf-8');
				echo $db->lastErrorMsg();
				exit(1);
			}

			$s = $db->prepare('INSERT INTO Answers(`CourseID`, Submitted, Data) VALUES (:id, :submitted, :data)');
			$s->bindValue(':id', 1, SQLITE3_INTEGER);
			$s->bindValue(':submitted', time(), SQLITE3_INTEGER);
			$s->bindValue(':data', json_encode($_POST), SQLITE3_TEXT);
			$result = $s->execute();
			if($result === false) {
				header('Content-Type: text/html; charset=utf-8');
				echo $db->lastErrorMsg();
				exit(1);
			}

			header('Location: /done.php');
			http_response_code(303);
			exit(0);
		}
	}
?><!doctype html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Questionario finale Corso GNU/Linux Base</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
			integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="cipolla.css">
</head>
<body>
<nav class="navbar navbar-expand-sm top">
	<div class="container">
		<a class="navbar-brand mr-3" href="/">
			Questionario corso GNU/Linux Base
		</a>
		<!--		<div class="ml-auto">-->
		<!--			<a href="language.php?l=en-us&from=--><? //=rawurlencode($_SERVER['REQUEST_URI'])?><!--">en</a>&nbsp;-&nbsp;<a-->
		<!--				href="language.php?l=it-it&from=--><? //=rawurlencode($_SERVER['REQUEST_URI'])?><!--">it</a>-->
		<!--		</div>-->
	</div>
</nav>

<div class="container">
	<form method="post">
		<div class="large-form-group" data-page="0">
			<h3>Informazioni personali</h3>
			<p>Compilare il questionario vale come richiesta dell'attestato di frequenza, che verrà spedito via email a tutti coloro che hanno raggiunto le presenze necessarie. Ringraziamo in ogni caso chi vorrà compilarlo pur non avendo i requisiti per conseguire l'attestato.</p>
			<p>C'è tempo <strong>fino all'8 gennaio</strong> per compilare il questionario. Gli attestati verranno spediti <strong>a partire da metà gennaio</strong>.</p>
			<p>Iniziamo con alcune informazioni personali.</p>
			<div class="form-group row">
				<label for="name" class="col-sm-2 col-form-label">Nome</label>
				<input required type="text" name="name" id="name" class="form-control col-sm-10">
			</div>
			<div class="form-group row">
				<label for="surname" class="col-sm-2 col-form-label">Cognome</label>
				<input required type="text" name="surname" id="surname" class="form-control col-sm-10">
			</div>
			<div class="form-group row">
				<label for="email" class="col-sm-2 col-form-label">Email</label>
				<input required type="email" name="email" id="email" class="form-control col-sm-10">
				<small id="emailHelp"
						class="form-text text-muted help-block col-sm-10">Lo stesso indirizzo email che hai usato per iscriverti, se possibile.</small>
			</div>
		</div>
		<div class="large-form-group" data-page="1">
			<?php
			require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Topic.php';
			$topics = [
				new Topic('1', 'DLx', 'Alcuni concetti chiave sulle licenze software, perché GNU/Linux'),
				new Topic('2', 'AndriManna', 'Installazione di Xubuntu su VirtualBox'),
				new Topic('3', 'AndriManna',
					'Primi passi con Xubuntu. Struttura del filesystem. Gestione del filesystem utilizzando la shell e la GUI (parte 1)'),
				new Topic('3', 'AUgo',
					'Componenti software di GNU/Linux, distribuzioni GNU/Linux, altri concetti di carattere generale'),
				new Topic('4', 'quel_tale', 'Installazione di pacchetti (apt e GUI)'),
				new Topic('4', 'quel_tale', 'Editor di testo da shell'),
				new Topic('4', 'Oiras0r', 'Italian Linux Society'),
				new Topic('5', 'whitone',
					'Gestione del filesystem utilizzando la shell (parte 2), redirezione dell’output'),
				new Topic('6', 'quel_tale', 'Gestione dei permessi (chmod, chown)'),
				new Topic('6', 'quel_tale',
					'Gestione di utenti e password, login e logout utente root (useradd, userdel, su, sudo)'),
				new Topic('6', 'AndriManna', 'Cose carine con youtube-dl e ffmpeg'),
				new Topic('7', 'Boz', 'Gestione dei servizi (systemd, journalctl)'),
				new Topic('7', 'quel_tale', 'Connessione a macchine remote (SSH)'),
				new Topic('8', 'AndriManna', 'GNU screen'),
				new Topic('8', 'AndriManna', 'Accenno agli script'),
				new Topic('8', 'quel_tale', 'Chi fa il software open source (e come fa a guadagnarsi da vivere)'),
			];
			?>
			<h3>Interesse per gli argomenti</h3>
			<p>Per ogni argomento puoi dare un voto da 1 ("non mi interessava e/o non ci ho capito nulla") a 4 ("molto interessante, molto bello, top!"). Puoi selezionare "non c'ero" se non hai seguito la lezione né dal vivo né in videolezione. Le risposte non veranno associate alle tue informazioni personali, non aver paura di giudicare.</p>
			<?php
			$votes = [
				1   => 'Non mi interessava',
				2   => 'Poco interessante',
				3   => 'Interessante',
				4   => 'Molto interessante',
				'X' => 'Non c\'ero',
			]
			?>
			<div class="form-check form-check-inline">
				<table class="table table-striped">
					<thead>
					<tr>
						<th>Argomento</th>
						<?php foreach($votes as $vote => $description): ?>
							<th scope="col"><?=is_numeric($vote) ? "$vote - " : ''?><?=$description?></th>
						<?php endforeach; ?>
					</tr>
					</thead>
					<tbody>
					<?php foreach($topics as $topic): /** @var Topic $topic */ ?>
						<tr>
							<?php
							$id = "Appreciation of $topic->title";
							$summary = $topic->summary();
							?>
							<td><?=$summary?></td>
							<?php foreach($votes as $vote => $description): ?>
								<td><input required class="form-check-input" type="radio" name="<?=$id?>"
											id="<?=$id?>+<?=$vote?>" value="<?=$vote?>"
											aria-label="<?="$summary: voto $vote, $description"?>"
											title="<?=$description?>"></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="form-group">
				<label for="Comment">Hai altri commenti o suggerimenti? C'è qualche argomento che avresti preferito approfondire di più? (opzionale)</label>
				<textarea name="Comment" id="Comment" class="form-control" maxlength="10000" rows="7"></textarea>
			</div>
		</div>
		<div class="large-form-group" data-page="2">
			<h3>Domande di comprensione</h3>
			<?php
			$questions = [
				'Se aggiorno il sistema, ad esempio con &quot;apt upgrade&quot;...'                         => [
					'Vengono aggiornati tutti i programmi, purché installati tramite il package manager',
					'Viene aggiornato il software di sistema, mentre i programmi hanno un proprio aggiornatore automatico',
					'Vengono aggiornati i programmi installati dall\'utente, ma non il software di sistema',
					'Viene aggiornato il database locale dei pacchetti disponibili, ma nessun software viene aggiornato'
				],
				'I file eseguibili dei programmi, in generale, si trovano nella directory...'               => [
					'/lib',
					'/var',
					'/bin',
					'/exe',
					'/usr/software',
					'/C/Documents and settings',
				],
				'Il comando &quot;grep&quot; serve a...'                                                    => [
					'Cercare del testo all\'interno di file o nell\'output di altri programmi',
					'Cercare file in base al nome del file stesso',
					'Generare testo casuale',
					'Contare il numero di lettere e parole in un file di testo',
					//'Gestire la replicazione dei gruppi (Group REPlication)',
				],
				'Il comando ssh serve a...'                                                                 => [
					'Salvare lo stato della macchina prima dello spegnimento (Save SHell)',
					'Avviare il rotore principale (Start Switfly the Helicopter)',
					'Cercare file su computer remoti, con un\'interfaccia da terminale (Search SHell)',
					'Connettersi ad altri computer ed eseguire comandi da terminale (Secure SHell)',
				],
				'systemd è un software che gestisce, tra le altre cose...'                                  => [
					'I &quot;daemon&quot; (servizi), ad esempio permette di avviarli o interromperli',
					'Le directory di sistema, ad esempio permette di impostare i permessi',
					'Il download di file da internet, ad esempio permette di scaricare video da YouTube',
					'I programmi installati sul computer, ad esempio permette di installare o disinstallare programmi',
				],
				'Su Linux, in particolare su Xubuntu, è possibile gestire i permessi di file e cartelle...' => [
					'Con il comando chmod, oppure dall\'interfaccia grafica',
					'Con il comando chmod, non esiste invece un\'interfaccia grafica per gestire i permessi',
					'Con il comando htop, oppure dall\'interfaccia grafica',
					'Con il comando htop, non esiste invece un\'interfaccia grafica per gestire i permessi',
					'Con il comando nano, oppure dall\'interfaccia grafica',
					'Con il comando nano, non esiste invece un\'interfaccia grafica per gestire i permessi',
				],
				'Il codice sorgente di Linux...'                                                            => [
					'Non è rilasciato pubblicamente, per ragioni di sicurezza',
					'È rilasciato pubblicamente e gratuitamente, ma è necessario pagare una licenza per modificarlo',
					'È rilasciato pubblicamente e gratuitamente, ma la licenza GPL proibisce di modificarlo',
					'È rilasciato pubblicamente e gratuitamente e può essere modificato da chiunque',
					'Non so cosa sia il codice sorgente',
				],
				'Il comando <code>ls &gt; esempio.txt</code>...'                                             => [
					'Crea un collegamento al file esempio.txt',
					'Salva nel file esempio.txt un elenco di file e cartelle',
					'Restituisce un errore, utilizzare &gt; è errato in quanto bisogna utilizzare &gt;&gt; in questo caso',
					'Restituisce un errore, il terminale si aspetta il nome di un comando dopo &gt; e non il nome di un file',
				],
			];
			?>
			<p>Niente paura, non è un esame! Scegli la risposta che ti sembra più corretta oppure "non lo so", non fa alcuna differenza relativamente all'attestato e le risposte non veranno associate alle tue informazioni personali.</p>

			<?php // shuffle_assoc($questions) ?>
			<?php foreach($questions as $question => $answers): ?>
				<?php shuffle($answers) ?>
				<div class="form-group">
				<p class="col-12"><?=$question?></p>
				<?php foreach($answers as $answer): ?>
					<div class="form-check">
						<input required class="form-check-input" type="radio" name="<?=$question?>"
								id="<?=$question?>+<?=$answer?>" value="<?=$answer?>">
						<label class="form-check-label" for="<?=$question?>+<?=$answer?>">
							<?=$answer?>
						</label>
					</div>
				<?php endforeach; ?>
					<div class="form-check">
						<input required class="form-check-input" type="radio" name="<?=$question?>" id="<?=$question?>-unknown" value="Non lo so">
						<label class="form-check-label" for="<?=$question?>-unknown">
							Non lo so
						</label>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="large-form-group" data-page="3">
			<h3>Consenso al trattamento dei dati</h3>
			<p>Acconsenti al <a href="tos.php">trattamento dei dati personali</a> come delineato nel documento relativo?</p>
			<div class="form-check form-group-row">
				<input required class="form-check-input" type="checkbox" id="consent" name="consent" value="yes">
				<label class="form-check-label" for="consent">Acconsento al trattamento dei dati personali</label>
			</div>
		</div>
		<button class="btn btn-primary d-none" id="btn-next-page">Pagina successiva</button>
		<button id="btn-submit" class="btn btn-primary my-3" type="submit">Invia</button>
		<button class="btn btn-outline-secondary d-none" id="btn-prev-page">Pagina precedente</button>
	</form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
		integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
		crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
		integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
		crossorigin="anonymous"></script>
<script>
	(function() {
		"use strict";

		let next = document.getElementById("btn-next-page");
		let prev = document.getElementById("btn-prev-page");
		let submit = document.getElementById("btn-submit");
		let pages = document.getElementsByClassName("large-form-group");

		if(typeof next.classList === "undefined" || typeof next.closest === "undefined" || typeof next.scrollIntoView === "undefined") {
			// Old browsers
			return;
		}

		let currentPage = 0;
		for(let i = 1; i < pages.length; i++) {
			pages[i].classList.add("d-none");
		}
		changePage(0);

		function changePage(to, alwaysSubmit) {
			if(to < 0 || to >= pages.length) {
				return;
			}

			pages[currentPage].classList.add("d-none");
			pages[to].classList.remove("d-none");
			currentPage = to;

			if(currentPage >= pages.length - 1) {
				next.classList.add("d-none");
				prev.classList.remove("d-none");
				submit.classList.remove("d-none");
			} else if(currentPage === 0) {
				next.classList.remove("d-none");
				prev.classList.add("d-none");
				submit.classList.add("d-none");
			} else {
				next.classList.remove("d-none");
				prev.classList.remove("d-none");
				submit.classList.add("d-none");
			}
			if(alwaysSubmit) {
				submit.classList.remove("d-none");
			}

			return pages[currentPage];
		}

		next.addEventListener('click', function(ev) {
			ev.preventDefault();
			changePage(currentPage + 1).scrollIntoView();
		});

		prev.addEventListener('click', function(ev) {
			ev.preventDefault();
			changePage(currentPage - 1).scrollIntoView();
		});

		submit.addEventListener('click', function(ev) {
			let invalid = document.querySelector('input:invalid');
			if(invalid !== null) {
				let closest = invalid.closest(".large-form-group");
				if(closest !== null) {
					changePage(parseInt(closest.dataset.page), true);
					invalid.scrollIntoView();
				}
			}
		});

	}());
</script>
</body>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
		integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</html>

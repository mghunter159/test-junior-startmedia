<!DOCTYPE html>
<html lang="ru">
<head>
  <title>Турнирная таблица - Startmedia</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/table.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="/">Startmedia</a>
</nav>

<?php 
	// Работа с данными
	include "libs/working.php"; 
?>

<div class="container">

  <h1>Турнирная таблица</h1>
  
<!-- Вставляем нашу таблицу в div с overflow-x: auto, чтобы была видна прокрутка таблицы на маленьких экранах-->
<div style="overflow-x: auto;">
 <table id="tournament" class="table table-striped">
    <thead>
    	<tr>
    		<!-- Пишем шапку таблицы с onclick для дальнейшей сортировки -->
    		<th onclick="sortTable(0)">ФИО</th>
    		<th onclick="sortTable(1)">Город</th>
    		<th onclick="sortTable(2)">Машина</th>
    		<!-- Берем из данных файла сколько максимум попыток и вставляем их в таблицу-->
    		<?php
    			$max_attempts = max(array_map("count", $attempts_results));
    			for ($i = 0; $i < $max_attempts; $i++) {
        			echo "<th onclick=\"sortTable(" . ($i + 3) . ")\">Попытка " . ($i + 1) . "</th>";
    			}
    		?>
    		<th onclick="sortTable(<?php echo $max_attempts + 3; ?>)">Итоговые очки</th>
    		<th onclick="sortTable(<?php echo $max_attempts + 4; ?>)">Место</th>
		</tr>
    </thead>
    <tbody>
    	<?php
    	// Переменная для определения места
    	$place = 1;
    	// Проходим по массиву данных и выводим
     foreach ($final_results as $id => $total_points) {
          $cars = getCarsData($id, $data_cars);
          if ($cars) {
              echo "<tr>";
              echo "<td>{$cars["name"]}</td>";
              echo "<td>{$cars["city"]}</td>";
              echo "<td>{$cars["car"]}</td>";
              for ($i = 0; $i < $max_attempts; $i++) {
                  echo "<td>" . ($attempts_results[$id][$i] ?? 0) . "</td>";
              }
              echo "<td>{$total_points}</td>";
              echo "<td>{$place}</td>";
              echo "</tr>";
              $place++;
          }
      }
      ?>
    </tbody>
  </table>
</div>

</div>

<!-- Скрипт для сортировки, можно было бы использовать datatable, будет проще и красивее-->
<script>
function sortTable(table, col, reverse) {
    // изменяем tHead
    let th = table.tHead;
    [...th.rows].forEach(row => { 
      [...row.cells].forEach((cell, index) => { 
          cell.classList.remove("asc");
          cell.classList.remove("desc");
          if (index === col){
             if(reverse) {
                cell.classList.add("desc");            
             } else {
                cell.classList.add("asc");            
             } 
          }  

        
      });
    });
    
    var tb = table.tBodies[0], // use `<tbody>` to ignore `<thead>` and `<tfoot>` rows
        tr = Array.prototype.slice.call(tb.rows, 0), // put rows into array
        i;
    reverse = -((+reverse) || -1);
    tr = tr.sort(function (a, b) { // sort rows
        if (!isNaN(parseFloat(a.cells[col].textContent)) && !isNaN(parseFloat(b.cells[col].textContent))) {
          let aVal = parseFloat(a.cells[col].textContent);
          let bVal = parseFloat(b.cells[col].textContent);
          return reverse * (aVal - bVal);
               
        } else {
          return reverse * (a.cells[col].textContent.trim() .localeCompare(b.cells[col].textContent.trim())
               );
        }
        
    });
    for(i = 0; i < tr.length; ++i) tb.appendChild(tr[i]); // append each row in order
}

function makeSortable(table) {
    var th = table.tHead, i;
    th && (th = th.rows[0]) && (th = th.cells);
    if (th) i = th.length;
    else return; // if no `<thead>` then do nothing
    while (--i >= 0) (function (i) {
        var dir = 1;
        th[i].addEventListener('click', function () {sortTable(table, i, (dir = 1 - dir))});
    }(i));
}

function makeAllSortable(parent) {
    parent = parent || document.body;
    var t = parent.getElementsByTagName('table'), i = t.length;
    while (--i >= 0) makeSortable(t[i]);
}

window.onload = function () {makeAllSortable();};
</script>
</body>
</html>
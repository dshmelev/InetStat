<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function start(){
jQuery.post("ports.php", {"ip": "192.168.1.41","date1": "2009-06-17","date2": "2009-06-17"}, function(data) {
document.all.resultat.innerHTML = data;
});
});
</script>
<p id=resultat></p>


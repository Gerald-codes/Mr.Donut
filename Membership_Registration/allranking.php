<?php 
session_start();
include("header.php");
include_once("../mysql_conn.php");
?>
<div class="container">
<span>Top Rated Donuts</span>
<?php
$qry = "SELECT *,AVG(r.Rank) AS AverageRank FROM Ranking r LEFT JOIN Product p ON r.ProductID=p.ProductID GROUP BY r.ProductID ORDER BY AverageRank DESC";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        echo "<div class='row' style='padding:5px'>";
        $product = "productDetails.php?pid=$row[ProductID]";
        $ranking = number_format($row["AverageRank"],1);
        echo "<div class='col-8'>";
        echo "<p><a href=$product>$row[ProductTitle]</a></p>";
        echo "<span style='font-weight: bold;'>$ranking⭐</span>";
        echo "</div>";
        // Right Column - Display the product's image 
        $img = "../Images/Products/$row[ProductImage]";
        echo "<div class='col-4'>";
        echo "<img src='$img'/>";
        echo "</div>";
        echo "</div>";
    }
}
?>
<span>See what our customers have to say about our donuts</span>
<?php
$qry = "SELECT * FROM Ranking r LEFT JOIN Product p ON r.ProductID=p.ProductID LEFT JOIN Shopper s ON r.ProductID=s.ProductID";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $_SESSION["Cart"]); // "i" - integer
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        echo "<div class='row' style='padding:5px'>";
        $product = "productDetails.php?pid=$row[ProductID]";
        // Right Column - Display the product's image 
        $img = "../Images/Products/$row[ProductImage]";
        echo "<div class='col-4'>";
        echo "<img src='$img'/>";
        echo "<br /><p><a href=$product>$row[ProductTitle]</a></p>";
        echo "</div>";
        echo "<div class='col-8'>";
        echo "$row[ShopperName] rates this $row[Rank]/5<br/>";
        echo '<p> says "$row[Comment]"</p>';
        echo "</div>";
        echo "</div>";
    }
}
?>
</div>
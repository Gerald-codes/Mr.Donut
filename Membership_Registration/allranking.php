<?php 
session_start();
include("../header.php");
include_once("../Database/mysql_conn.php");
?>
<div class="container">
<h2 style="text-align:center">Top Ranked Donuts</h2>
<?php
$qry = "SELECT *,AVG(r.Rank) AS AverageRank FROM Ranking r LEFT JOIN Product p ON r.ProductID=p.ProductID GROUP BY r.ProductID ORDER BY AverageRank DESC";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
echo "<div class='row'>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $product = "../Product_Catalogue/productDetails.php?pid=$row[ProductID]";
        $ranking = number_format($row["AverageRank"],1);
        $img = "../Images/Products/$row[ProductImage]";
        echo "<div class='card' style='width:25%;margin-right:10px'>";
        echo "<img class='card-img-top' src='$img'>";
        echo "<div class='card-body'>";
        echo "<b class='card-text'><a href=$product>$row[ProductTitle]</a></b><br/>";
        echo "<span style='font-weight: bold;'>$ranking ⭐</span></div></div>"; 
    }
}
echo "</div>";
?>
<h3 style="text-align:center">Customer's Reviews</h3>
<?php
$qry = "SELECT * FROM Ranking r LEFT JOIN Product p ON r.ProductID=p.ProductID LEFT JOIN Shopper s ON r.ShopperID=s.ShopperID";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        echo "<div class='row' style='padding:5px'>";
        $product = "../Product_Catalogue/productDetails.php?pid=$row[ProductID]";
        // Right Column - Display the product's image 
        $img = "../Images/Products/$row[ProductImage]";
        echo "<div class='col-4'>";
        echo "<img src='$img'/>";
        echo "<br /><p><a href=$product>$row[ProductTitle]</a></p>";
        echo "</div>";
        echo "<div class='col-8'>";
        echo "$row[Name] rates this $row[Rank]/5<br/>";
        echo "Comment: $row[Comment]</p>";
        echo "</div>";
        echo "</div>";
    }
}
?>
</div>
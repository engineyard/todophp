<h1>Listing lists</h1>

<table>
  <tr>
    <th>Name</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>


<?php foreach($lists as $list) { ?>
  <tr>
    <td><?php echo($list->name) ;?></td>
    <td>show</td>
    <td>edit</td>
    <td></td>
  </tr>
<?php } ?>
</table>

<br />

<?php echo $this->html->link('New List', '/lists/new'); ?>



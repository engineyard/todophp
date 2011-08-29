<h1>Listing lists</h1>

<table>
  <tr>
    <th>Name</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>

<% @lists.each do |list| %>
  <tr>
    <td><%= list.name %></td>
    <td><%= link_to 'Show', list %></td>
    <td><%= link_to 'Edit', edit_list_path(list) %></td>
    <td></td>
  </tr>
<% end %>
</table>

<br />

<?php echo $this->html->link('Google', 'http://www.google.com'); ?>
<%= link_to 'New List', new_list_path %>


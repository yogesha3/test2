Hi John,
<table>
    <tr>
        <th colspan="2" style="text-align: left">Feedback Form Detail </th>
    </tr>
    <tr>
        <td>Name:</td>
        <td><?php echo (!empty($data['name'])) ? $data['name']: '-';?></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><?php echo (!empty($data['email'])) ? $data['email']: '-';?></td>
    </tr>
    <tr>
        <td>Company:</td>
        <td><?php echo (!empty($data['company'])) ? $data['company']: '-';?></td>
    </tr>
    <tr>
        <td>Phone:</td>
        <td><?php echo (!empty($data['phone'])) ? $data['phone']: '-';?></td>
    </tr>
    <tr>
        <td>Number of employees:</td>
        <td><?php echo (!empty($data['employeesNumber'])) ? $data['employeesNumber']: '-';?></td>
    </tr>
    <tr>
        <td>comment:</td>
        <td><?php echo (!empty($data['comment'])) ? $data['comment']: '-';?></td>
    </tr>
   
</table>

Thank you
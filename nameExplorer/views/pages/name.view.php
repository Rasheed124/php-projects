 
 <h3>Statistics for the name: <?php echo e($name); ?></h3>
 
 <?php if (! empty($user_lists)): ?>
         <table>
            <tr>
                <th>Name</th>
                <th>Year</th>
                <th>Count</th>
            </tr>
        
                <?php foreach ($user_lists as $user): ?>
                    <tr>
                    <td >
                        <?php echo e($user['name']) ?>
                    </td>
                    <td >
                        <?php echo e($user['year']) ?>
                    </td>
                    <td >
                        <?php echo e($user['count']) ?>
                    </td>
                    </tr>
                <?php endforeach; ?>
         </table>
     <?php else: ?>
         <h2>No user Found</h2>
     <?php endif; ?>
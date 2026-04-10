<?php $__env->startSection('title', 'Pending Appraisal'); ?>
<?php $__env->startSection('breadcrumb', 'Pending Appraisal'); ?>
<?php $__env->startSection('page-title', 'Appraisal Section'); ?>

<?php $__env->startSection('content'); ?>

<head>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<style>
.top {

    display: flex;
    width: 100%;
    flex-direction: column;

}

.dataTables_filter {
    display: none;
}
</style>
<div class="client">
    <h1 class="client__heading">Pending Appraisal</h1>
    <div class="client___item">
        <input type="search" id="employee_search" name="search" class="form-control client__search" placeholder="Search"
            aria-label="Search">
        <button class="client__btn" type="submit">
                            <img src="<?php echo e(url('images/search.png')); ?>" alt="Search">
        </button>
    </div>
</div>
    <div class="container table-container pending-appraisal-table">
        <div class="table-responsive table-wrapper">
            <table id="pending-apprasial" class="table table-bordered table-hover main-table table-view">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee Id</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>Joining Date</th>
                        <th>Financial Year</th>
                        <th>Probation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->fname); ?> <?php echo e($user->lname); ?></td>
                        <td><?php echo e($user->employee_id); ?></td>
                        <td><?php echo e($user->designation); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td><?php echo e($user->dob); ?></td>
                        <td><?php echo e($user->financial_year); ?></td>
                        <td><?php echo e($user->probation_date ?? 'Not Set'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
    // $(document).ready(function () {
    //     // Initialize the DataTable
    //     let table = $('#pending-apprasial').DataTable({
    //         dom: '<"top"lfr>t<"bottom"ip><"clear">',
    //         paging: true,
    //         searching: true,
    //         ordering: true,
    //         info: true,
    //        "lengthChange": false,
    //         pageLength: 10,
    //         initComplete: function () {
    //             const filterDiv = $('div.dataTables_filter');
    //             const label = filterDiv.find('label');
    //             const input = label.find('input');

    //             // Add a new label element
    //             const customLabel = $('<label style="margin-right: 10px; font-weight: bold;">Search User:</label>');
    //             filterDiv.prepend(customLabel);

    //             label.after(input);
    //             label.remove();

    //             filterDiv.css({
    //                 'display': 'flex',
    //                 'align-items': 'center',
    //                 'gap': '10px',
    //                 'justify-content': 'flex-start',
    //                 'margin-bottom': '10px'
    //             });

    //             // Style the search input
    //             input.attr('placeholder', 'Type to search...');
    //             input.css({
    //                 'padding': '6px',
    //                 'border-radius': '4px',
    //                 'border': '1px solid #ccc',
    //                 'width': '200px'
    //             });
    //         }


    //     });

    //     // Function to fetch and update the DataTable with filtered data by financial year
    //     function fetchAppraisalPendingList(yearRange) {
    //         if (!yearRange) {
    //             console.log("No year selected");
    //             return;
    //         }
    //         $.ajax({
    //             url: '/filter-by-financial-year',
    //             method: 'POST',
    //             data: { financial_year: yearRange, _token: '<?php echo e(csrf_token()); ?>' },
    //             success: function (response) {
    //                 // Clear existing rows in the DataTable
    //                 table.clear();

    //                 // Check if the response contains any data
    //                 if (response.data && response.data.length === 0) {
    //                     alert("No users found for the selected financial year.");
    //                     return;
    //                 }

    //                 // Add the new filtered data to the DataTable
    //                 response.data.forEach(function (user) {
    //                     table.row.add([
    //                         user[0], // Full Name
    //                         user[1], // User ID
    //                         user[2], // Designation
    //                         user[3], // Email
    //                         user[4], // Joining Date (DOB)
    //                         user[5], // Financial Year
    //                         user[6]  // Probation Date
    //                     ]).draw();
    //                 });
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error("Error fetching data: " + error);
    //             }
    //         });
    //     }

    //     // When the financial year dropdown value changes, call the fetch function
    //     $(document).on('change', '#financialYearFilter', function () {
    //         const yearRange = $(this).val();
    //         fetchAppraisalPendingList(yearRange); // Fetch the filtered list based on the selected year
    //     });

    //     // Optional: You can trigger the AJAX call immediately when the page loads if a default year is selected
    //     const defaultYear = $('#financialYearFilter').val();
    //     if (defaultYear) {
    //         fetchAppraisalPendingList(defaultYear);
    //     }
    // });

    $(document).ready(function() {
        // Initialize the DataTable
        let table = $('#pending-apprasial').DataTable({
            dom: '<"top"lfr>t<"bottom"ip><"clear">',
            paging: true,
            searching: true, // required for custom search to work
            ordering: true,
            info: true,
            lengthChange: false,
            pageLength: 10,
            language: {
                emptyTable: "No employee data available"
            }
        });

        // ✅ Custom search input logic
        $('#employee_search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // ✅ AJAX filtering logic
        function fetchAppraisalPendingList(yearRange) {
            if (!yearRange) return;

            $.ajax({
                url: '/filter-by-financial-year',
                method: 'POST',
                data: {
                    financial_year: yearRange,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    table.clear();
                    if (!response.data || response.data.length === 0) {
                        alert("No users found for the selected financial year.");
                        return;
                    }

                    response.data.forEach(function(user) {
                        table.row.add([
                            user[0],
                            user[1],
                            user[2],
                            user[3],
                            user[4],
                            user[5],
                            user[6]
                        ]);
                    });

                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: " + error);
                }
            });
        }

        $(document).on('change', '#financialYearFilter', function() {
            fetchAppraisalPendingList($(this).val());
        });

        const defaultYear = $('#financialYearFilter').val();
        if (defaultYear) {
            fetchAppraisalPendingList(defaultYear);
        }
    });
    </script>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home3/delose1a/evalon.delostylestudio.co.in/resources/views/admin/appraisalPendingList.blade.php ENDPATH**/ ?>
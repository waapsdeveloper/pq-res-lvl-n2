<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Auto Hit</title>
    <style>
        /* Basic body and button styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        button:disabled {
            background-color: #ccc;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10 px;
            text-align: left;
            font-size: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) {
            background-color: #e9f7f2;
        }

        .order-id {
            font-weight: bold;
        }

        /* Small size rows */
        td,
        th {
            padding: 8px;
            font-size: 12px;
            /* Smaller text */
        }

        tr {
            height: 15px;
            padding: 5px;
            font-size: 10px
        }
    </style>
</head>

<body>

    <button id="startButton">Start</button>
    <button id="stopButton" disabled>Stop</button>

    <table id="ordersTable">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Status</th>
                <th>Total amount</th>

                <th>Invoice</th>
            </tr>
        </thead>
        <tbody>
            <!-- Orders will be displayed here -->
        </tbody>
    </table>

    <script>
        let intervalId; // This will hold the reference to the interval

         // Call fetchOrders when the page loads
         window.onload = function() {
        fetchOrders(); // Fetch previous orders on page load
        
        }  

        
       document.getElementById('startButton').addEventListener('click', function () {
        // Disable the start button and enable the stop button
        document.getElementById('startButton').disabled = true;
        document.getElementById('stopButton').disabled = false;
        
        // Start hitting both APIs every 10 seconds
        intervalId = setInterval(function () {
            // Hit both APIs simultaneously
            {{--  makeOrder();  --}}
            fetchOrders();
        }, 3500); // 10 seconds interval
        });  

       document.getElementById('stopButton').addEventListener('click', function () {
        // Disable the stop button and enable the start button
        document.getElementById('stopButton').disabled = true;
        document.getElementById('startButton').disabled = false;
        
        // Clear the interval and stop the API requests
        clearInterval(intervalId);
        });

        // Function to hit the "make-order" API
        function makeOrder() {
            fetch('http://127.0.0.1:8000/api/make-order')  // Replace with your make-order API URL
                .then(response => response.json())
                .then(data => {
                    {{--  console.log('Make order API response:', data);  --}}
                    // Handle the response if needed (e.g., show success/failure)
                })
                .catch(error => {
                    console.error('Error with make-order API:', error);
                });
        }

        // Function to fetch orders from the "get-orders" API
        function fetchOrders() {
            fetch('http://127.0.0.1:8000/api/get-orders')  // Replace with your get-orders API URL
                .then(response => response.json())
                .then(data => {
                    {{--  console.log('Get orders API response:', data);  --}}

                    // Assuming the API response contains an array of orders, with an 'id' field
                    if (data && data.orders) {
                        const orders = data.orders;

                        // Sort orders by ID in descending order
                        orders.sort((a, b) => b.id - a.id);

                        // Display orders on the page
                        displayOrders(orders);
                    } else {
                        console.error('No orders in API response.');
                    }
                })
                .catch(error => {
                    console.error('Error with get-orders API:', error);
                });
        }

        // Function to display orders in table format
        function displayOrders(orders) {
            const ordersTableBody = document.getElementById('ordersTable').getElementsByTagName('tbody')[0];
            ordersTableBody.innerHTML = ''; // Clear the existing rows

            // Loop through orders and add them as rows in the table
            orders.forEach(order => {
                const row = document.createElement('tr');

                const orderIdCell = document.createElement('td');
                orderIdCell.textContent = order.id;
                row.appendChild(orderIdCell);

                const orderStatusCell = document.createElement('td');
                orderStatusCell.textContent = order.status;
                row.appendChild(orderStatusCell);

                const orderTotalPrice = document.createElement('td');
                orderTotalPrice.textContent = order.total_price;
                row.appendChild(orderTotalPrice);

                const orderInvoiceCell = document.createElement('td');
                orderInvoiceCell.textContent = order.invoice_no;
                row.appendChild(orderInvoiceCell);

               

                ordersTableBody.appendChild(row);
            });
        }
    </script>

</body>

</html>
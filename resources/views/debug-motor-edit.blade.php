<!DOCTYPE html>
<html>
<head>
    <title>Debug Motor Edit Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Debug Motor Edit Form - PATCH Method Test</h1>
    
    <div style="background: #f0f0f0; padding: 20px; margin: 20px 0;">
        <h3>Current Form Configuration:</h3>
        <ul>
            <li><strong>Route:</strong> {{ route('pemilik.motor.update', 18) }}</li>
            <li><strong>Expected Method:</strong> PATCH</li>
            <li><strong>CSRF Token:</strong> {{ csrf_token() }}</li>
        </ul>
    </div>
    
    <form action="{{ route('pemilik.motor.update', 18) }}" method="POST" style="border: 2px solid green; padding: 20px;">
        @csrf
        @method('PATCH')
        
        <h4>Test Form (PATCH Method):</h4>
        <input type="text" name="brand" value="Honda" placeholder="Brand" required><br><br>
        <input type="text" name="model" value="Vario" placeholder="Model" required><br><br>
        <select name="type_cc" required>
            <option value="125cc">125cc</option>
        </select><br><br>
        <input type="number" name="year" value="2023" min="2000" max="2024" required><br><br>
        <input type="text" name="color" value="Merah" placeholder="Color" required><br><br>
        <input type="text" name="plate_number" value="B 1234 TEST" placeholder="Plate Number" required><br><br>
        
        <button type="submit" style="background: green; color: white; padding: 10px 20px; border: none;">
            UPDATE MOTOR (PATCH METHOD)
        </button>
    </form>
    
    <div style="background: #ffe6e6; padding: 20px; margin: 20px 0;">
        <h3>Debug Information:</h3>
        <ul>
            <li>View compiled at: {{ date('Y-m-d H:i:s') }}</li>
            <li>Laravel version: {{ app()->version() }}</li>
            <li>Environment: {{ app()->environment() }}</li>
        </ul>
    </div>
    
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form Method:', this.method);
            console.log('Form Action:', this.action);
            console.log('Hidden Method Field:', this.querySelector('input[name="_method"]').value);
            
            // Don't prevent default - let it submit
        });
    </script>
</body>
</html>
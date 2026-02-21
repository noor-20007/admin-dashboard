<!DOCTYPE html>
<html>
<head>
    <title>File Manager Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>File Manager API Test</h1>
    
    <div id="status"></div>
    <div id="result"></div>
    
    <script>
        const statusDiv = document.getElementById('status');
        const resultDiv = document.getElementById('result');
        
        // Test 1: Check CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        statusDiv.innerHTML += `<p>CSRF Token: ${csrfToken ? '✅ Found' : '❌ Missing'}</p>`;
        
        // Test 2: Check Authentication
        fetch('/filemanager/list?path=', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            credentials: 'same-origin'
        })
        .then(response => {
            statusDiv.innerHTML += `<p>Response Status: ${response.status}</p>`;
            
            if (response.status === 401) {
                statusDiv.innerHTML += `<p>❌ Not authenticated. Please <a href="/login">login</a> first.</p>`;
                return null;
            }
            
            if (response.status === 419) {
                statusDiv.innerHTML += `<p>❌ CSRF Token mismatch. Try clearing cache.</p>`;
                return null;
            }
            
            if (!response.ok) {
                statusDiv.innerHTML += `<p>❌ Error: ${response.statusText}</p>`;
                return response.text();
            }
            
            statusDiv.innerHTML += `<p>✅ API Response OK</p>`;
            return response.json();
        })
        .then(data => {
            if (data) {
                resultDiv.innerHTML = '<h2>API Response:</h2><pre>' + JSON.stringify(data, null, 2) + '</pre>';
                
                if (data.success) {
                    statusDiv.innerHTML += `<p>✅ File Manager is working!</p>`;
                    statusDiv.innerHTML += `<p><a href="/filemanager">Go to File Manager</a></p>`;
                } else {
                    statusDiv.innerHTML += `<p>❌ API returned error: ${data.message}</p>`;
                }
            } else if (resultDiv.innerHTML === '') {
                resultDiv.innerHTML = '<p>Check status messages above</p>';
            }
        })
        .catch(error => {
            statusDiv.innerHTML += `<p>❌ Network Error: ${error.message}</p>`;
            console.error('Error:', error);
        });
    </script>
</body>
</html>

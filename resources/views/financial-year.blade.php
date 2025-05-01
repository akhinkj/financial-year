<!DOCTYPE html>
<html>
<head>
    <title>Financial Year Calculator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .container { max-width: 800px; margin: 50px auto; }
        select { padding: 8px; margin: 10px; width: 200px; }
        #result { margin-top: 20px; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <select id="country">
            <option value="">Select Country</option>
            <option value="UK">United Kingdom</option>
            <option value="IE">Ireland</option>
        </select>

        <select id="year">
            <option value="">Select Year</option>
        </select>

        <div id="result"></div>
    </div>

    <script>
        $(document).ready(function() {
            const currentYear = new Date().getFullYear();
            
            $('#country').change(function() {
                const country = $(this).val();
                $('#year').html('<option value="">Select Year</option>');
                
                if (country) {
                    const startYear = currentYear - 10;
                    const options = [];
                    
                    for (let year = startYear; year <= currentYear; year++) {
                        if (country === 'UK') {
                            const formatted = `${year}-${String(year + 1).slice(-2)}`;
                            options.push(`<option value="${year}">${formatted}</option>`);
                        } else {
                            options.push(`<option value="${year}">${year}</option>`);
                        }
                    }
                    
                    $('#year').append(options.join(''));
                }
            });

            $('#year').change(function() {
                const country = $('#country').val();
                const year = $(this).val();
                
                if (country && year) {
                    $.ajax({
                        url: '/get-details',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            country: country,
                            year: year
                        },
                        success: function(response) {
                            let html = `
                                <h3>Financial Year</h3>
                                <p>Start: ${response.start}</p>
                                <p>End: ${response.end}</p>
                                <h3>Public Holidays</h3>
                            `;
                            
                            if (response.holidays.length > 0) {
                                html += '<ul>';
                                response.holidays.forEach(holiday => {
                                    html += `<li>${holiday.name} - ${holiday.date}</li>`;
                                });
                                html += '</ul>';
                            } else {
                                html += '<p>No public holidays found</p>';
                            }
                            
                            $('#result').html(html);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
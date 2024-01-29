<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .control{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #ffffff;
    
            width:250px;
            height:250px;
        }

        /* demo */
        body {
            margin: 0;
            display: grid;
            place-items: center;
            height: 100vh;
            background: #f0f8ff;
        }

        .container { 
            background-color: rgb(192, 192, 192); 
            width: 80%; 
            border-radius: 15px; 
        } 
  
        .skill { 
            background-color: rgb(116, 194, 92); 
            color: white; 
            padding: 1%; 
            text-align: right; 
            font-size: 20px; 
            border-radius: 15px; 
        } 

    </style>
</head>
<body>
    <div class="control">
        <form action="" method="post" id="form-upload">
            <input type="file" name="video" id="video">
            <button type="submit">send</button>
        </form>
    </div>

    <div class="container"> 
        <div class="skill php" id="progress" style="width:0%;">0%</div> 
    </div> 

    <script>
        document.getElementById("form-upload").addEventListener("submit",function (e){
            e.preventDefault();

            const CHUNK_SIZE = 1000000; // 1MB
            let start = 0;
            let end = CHUNK_SIZE;
            let chunks = [];
            let file = document.getElementById('video').files[0];

            while (start < file.size) {
                let chunk = file.slice(start, end);
                chunks.push(chunk);
                start = end;
                end = start + CHUNK_SIZE;
            }

            let  i=0;
            chunks.forEach((chunk, index) => {
                let formData = new FormData();
                formData.append('file', chunk);
                formData.append('name', file.name);
                formData.append('index', index);

                fetch('upload.php', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    i++;
                    // Handle response
                    iter=(i/chunks.length) * 100
                    document.getElementById("progress").style.width=`${iter}%`
                    document.getElementById("progress").innerHTML=`${iter}%`
                }).catch(error => {
                    // Handle error
                });
            });
        })
    </script>
</body>
</html>
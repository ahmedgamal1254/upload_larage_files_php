<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            height:100vh;
        }
        .control{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #ffffff;
            box-shadow:#000000 1px 0 10px;
            width:250px;
            height:250px;
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

    <progress id="progress" value="0" max="100"> 0% </progress>

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
                    console.log(iter)
                    document.getElementById("progress").value=iter

                }).catch(error => {
                    // Handle error
                });
            });
        })
    </script>
</body>
</html>
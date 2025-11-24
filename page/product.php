
<?php
require '../_base.php';

$_title = 'Page | Demo 1';
include '../_head.php';
?>
      
<style>
    #img {
        width: 400px;
        height: 250px;
        border: 10px solid #fff;
        box-shadow: 0 0 5px #000;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<p id="p">Image 1 of 4</p>
<img id="img" src="/images/1.jpg">

<script>
    const arr = [
        '/images/1.jpg',
        '/images/2.jpg',
        '/images/3.jpg',
        '/images/4.jpg'
    ];

    let i = 0;

    $('#img').on('click', e => {
        i = ++i % arr.length;

        $('#p').text(`Image ${i + 1} of 4`);

        $('#img')
            .hide()
            .prop('src', arr[i])
            .fadeIn();
    });
</script>

<?php
include '../_foot.php';
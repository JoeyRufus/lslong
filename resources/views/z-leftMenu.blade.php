<style>
    .left-menu {
        position: fixed;
        left: -90px;
        top: 300px;
        width: 100px;
        padding: 10px 0;
        border-radius: 0 10px 10px 0;
        z-index: 9;
        background: #FF6600;
        font-size: 1.2rem;
        transition: 500ms;
    }

    .left-menu:hover {
        left: 0;
    }

    .left-menu a {
        display: block;
        padding: 5px;
        margin: 0 auto;
        cursor: pointer;
        transition: 200ms;
    }

    .left-menu a:hover {
        color: #fff;
    }

    .left-menu i {
        width: 25px;
        text-align: center
    }
</style>
<div class="left-menu">
    <a href='/'><i class="fas fa-home"></i>主页<i class="fas fa-angle-double-right"></i></a>
    <a href="/website"><i class="fas fa-server"></i>网站<i class="fas fa-angle-double-right"></i></a>
    <a href="/blog"><i class="fas fa-blog"></i>博客<i class="fas fa-angle-double-right"></i></a>
    <a href="/exp"><i class="fas fa-book"></i>经验<i class="fas fa-angle-double-right"></i></a>
    <a href="/shop"=""><i class="fas fa-shopping-cart"></i>购物<i class="fas fa-angle-double-right"></i></a>
</div>

const container = document.getElementById('container');
const loading = document.querySelector('.loading');
const btn = document.getElementById('back-button');

// getPost();
// getPost();
var nump = 0;
getPost(nump);

window.addEventListener('scroll', () => {
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;

    // console.log( { scrollTop, scrollHeight, clientHeight });
    btn.classList.add('show');

    if (clientHeight + scrollTop >= scrollHeight - 5) {
        // show the loading animation
        showLoading();
    }
});

function showLoading() {
    loading.classList.add('show');
    // load more data
    setTimeout(function () {
        getPost(nump += 1);
    }, 1000)
}

function topFunction() {

    window.scroll({
        top: 0,
        behavior: 'smooth'
    });

}



const url = "http://192.168.99.100";
const template = post => {
    return `
    <div class="card"  onload="checklike(${post.post_id}">
    <div class="card-header">
        <div class="columns">
            <div class="column">
                <figure class="image is-24x24">
                    <img class="is-rounded" src="${url}/camagru/public/img/profile.gif">
                </figure>
            </div>
            <div class="column is-11">
                <a href="${url}/camagru/users/profile/${post.user_id}">
                    <h4>${post.username}</h4>
                </a>
            </div>
            <div class="column is-4 is-offset-8">
            </div>
        </div>
    </div>
    <div class="card-content">
        <img class="gallery-image" src="${url}/${post.images}">
    </div>
    <div class="card-footer">
        <div class="columns">
           <div class="column is-11 is-offset-1">
            <div class="columns">
            <div class="column is-two-fifths ${post.liked ? 'has-text-danger' : ''} " onclick="likeButton(${post.post_id})">
                <span class="icon">
                    <i class="fa fa-heart"></i>
                </span>
                <span class="" id="like${post.post_id}" class="like${post.post_id}">${post.likes} likes</span>

            </div>

            <div class="column is-6">
                <span class="icon">
                    <i class="far fa-comments"></i>
                </span>
                <span> ${post.comments} Comments</span>
            </div>

            <div class="column is-two-fifths">
                <div class="buttons are-small">
                    <a href="${url}/camagru/posts/show/${post.post_id}" class="href"><button class="button is-info is-inverted" class="btncmd" data-dismiss="modal">Show details</button></a>
                </div>
            </div>
           </div>
           </div>
        </div>
    </div>


</div> <br>
	`;
}



async function getPost(page) {
    const postResponse = await fetch(`http://192.168.99.100/camagru/posts/page/${page}`);
    const postData = await postResponse.json();
    const data = { post: postData };

    addDataToDOM(data);
}


function addDataToDOM(data) {
    const postElement = document.createElement('div');
    postElement.classList.add('blog-post');

    data = data.post.posts;
    data.forEach(element => {
        const item = document.createElement('div')
        item.classList.add('gallery-item');
        item.innerHTML = template(element);
        container.appendChild(item);
    });

    container.appendChild(postElement);

    loading.classList.remove('show');
}

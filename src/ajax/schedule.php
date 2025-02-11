<div id="schedule-block"><section class="block_area block_area_sidebar block_area-schedule schedule-full">
    <div class="block_area-header">
        <div class="float-left bah-heading mr-4">
            <h2 class="cat-heading">Estimated Schedule</h2>
        </div>
        <div class="float-left bah-time">
            <span class="current-time">
                <span id="timezone"></span> 
                <span id="current-date"></span> 
                <span id="clock"></span>
            </span>
        </div>
        <script>
            function updateTime() {
                const now = new Date();
                const timezoneOffset = -now.getTimezoneOffset() / 60;
                const timezone = `GMT${timezoneOffset >= 0 ? '+' : ''}${timezoneOffset}:00`;
                const currentDate = now.toLocaleDateString();
                const currentTime = now.toLocaleTimeString();

                document.getElementById('timezone').textContent = `(${timezone})`;
                document.getElementById('current-date').textContent = currentDate;
                document.getElementById('clock').textContent = currentTime;
            }

            setInterval(updateTime, 1000);
            updateTime();
        </script>
        <div class="clearfix"></div>
    </div>
    <div class="block_area-content">
        <div class="table_schedule">
            <div class="table_schedule-date">
                <div class="swiper-container swiper-container-initialized swiper-container-horizontal">
                    <div class="swiper-wrapper" id="schedule-dates">
                        <!-- Date slides will be populated dynamically -->
                    </div>
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                <div class="ts-navigation">
                    <button class="btn tsn-next" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false"><i class="fas fa-angle-right"></i></button>
                    <button class="btn tsn-prev" tabindex="0" role="button" aria-label="Previous slide" aria-disabled="false"><i class="fas fa-angle-left"></i></button>
                </div>
            </div>
            <div class="clearfix"></div>
            <ul class="ulclear table_schedule-list limit-8" id="schedule-items">
                <!-- Schedule items will be populated dynamically -->
            </ul>
            <button id="scl-more" class="btn btn-sm btn-block btn-showmore" style="display: none;"></button>
        </div>
    </div>
</section>
</div>
<script>
const API_BASE_URL = '<?=$zpi?>';
const AJAX_BASE_URL = '/ajax/schedule.php';

function generateDates() {
    const dates = [];
    const today = new Date();
    
    for(let i = 0; i < 31; i++) {
        const date = new Date();
        date.setDate(today.getDate() + i);
        
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        dates.push({
            dayName: dayNames[date.getDay()],
            monthName: monthNames[date.getMonth()],
            date: date.getDate(),
            fullDate: date.toISOString().split('T')[0]
        });
    }
    return dates;
}


function renderDates(dates) {
    const container = document.getElementById('schedule-dates');
    const today = new Date().toISOString().split('T')[0];
    
    dates.forEach(date => {
        const isActive = date.fullDate === today;
        container.innerHTML += `
            <div class="swiper-slide day-item" data-date="${date.fullDate}" style="width: 163.8px; margin-right: 10px;">
                <div class="tsd-item ${isActive ? 'active' : ''}">
                    <span>${date.dayName}</span>
                    <div class="date">${date.monthName} ${date.date}</div>
                </div>
            </div>
        `;
    });
}

async function fetchSchedule(date) {
    try {
        const tzOffset = new Date().getTimezoneOffset();
        const response = await fetch(`${API_BASE_URL}/schedule?date=${date}&tzOffset=${tzOffset}`);
        const data = await response.json();
        const scheduleItems = data.results;
        
        const container = document.getElementById('schedule-items');
        container.innerHTML = '';
        
        scheduleItems.forEach(item => {
            container.innerHTML += `
                <li>
                    <a href="/details/${item.id}" class="tsl-link">
                        <div class="time">${item.time}</div>
                        <div class="film-detail">
                            <h3 class="film-name dynamic-name" data-jname="${item.japanese_title}">${item.title}</h3>
                            <div class="fd-play">
                                <button type="button" class="btn btn-sm btn-play">
                                    <i class="fas fa-play mr-2"></i>Episode ${item.episode_no}
                                </button>
                            </div>
                        </div>
                    </a>
                </li>
            `;
        });

        if (scheduleItems.length > 7) {
            document.getElementById('scl-more').style.display = 'block';
        } else {
            document.getElementById('scl-more').style.display = 'none';
        }
    } catch (error) {
        console.error('Error fetching schedule:', error);
    }
}


const dates = generateDates();
renderDates(dates);

var scheduleSw = new Swiper('.schedule-full .table_schedule-date .swiper-container', {
    slidesPerView: 7,
    spaceBetween: 10,
    navigation: {
        nextEl: '.schedule-full .tsn-next',
        prevEl: '.schedule-full .tsn-prev',
    },
    breakpoints: {
        320: {
            slidesPerView: 3,
            spaceBetween: 10,
        },
        360: {
            slidesPerView: 3,
            spaceBetween: 10,
        },
        480: {
            slidesPerView: 3,
            spaceBetween: 10,
        },
        640: {
            slidesPerView: 4,
            spaceBetween: 10,
        },
        768: {
            slidesPerView: 5,
            spaceBetween: 10,
        },
        1024: {
            slidesPerView: 7,
            spaceBetween: 13,
        },
    },
});


document.querySelectorAll('.day-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.tsd-item').forEach(el => el.classList.remove('active'));
        this.querySelector('.tsd-item').classList.add('active');
        fetchSchedule(this.dataset.date);
    });
});


fetchSchedule(new Date().toISOString().split('T')[0]);


$("#scl-more").click(function () {
    $(this).parent().find(".limit-8").toggleClass("active");
    $(this).toggleClass("active");
});
</script>
<script>
    $("#scl-more").click(function () {
        $(this).parent().find(".limit-8").toggleClass("active");
        $(this).toggleClass("active");
    });

    if ($('.table_schedule-list li').length > 7) {
        $('#scl-more').show();
    }

    var scheduleSw = new Swiper('.schedule-full .table_schedule-date .swiper-container', {
        slidesPerView: 7,
        spaceBetween: 10,
  
        navigation: {
            nextEl: '.schedule-full .tsn-next',
            prevEl: '.schedule-full .tsn-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            360: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            480: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            640: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            1024: {
                slidesPerView: 7,
                spaceBetween: 13,
            },
        },
    });
    scheduleSw.slideTo($(".tsd-item").index($(".tsd-item.active")), 1000);
    setTimeout(function () {
        $(".tsd-item.active").click();
    }, 1000)
    $('.day-item').click(function () {
        var tzOffset = new Date().getTimezoneOffset();
        $('.tsd-item').removeClass('active');
        $(this).find('.tsd-item').addClass('active');
        $.get(`${AJAX_BASE_URL}/schedule/list`, {
            tzOffset: tzOffset,
            date: $(this).data('date')
        }, function (res) {
            if(res){
                $('.table_schedule-list').html(res.html);
                if ($('.table_schedule-list li').length > 7) {
                    $('#scl-more').show();
                } else {
                    $('#scl-more').hide();
                }
            }
        });
    });

    setInterval(showTime, 1000);

    function showTime() {
        var time = new Date();
        var hour = time.getHours();
        var min = time.getMinutes();
        var sec = time.getSeconds();
        var am_pm = "AM";

        if (hour > 12) {
            hour -= 12;
            am_pm = "PM";
        }
        if (hour === 0) {
            hour = 12;
            am_pm = "AM";
        }

        hour = hour < 10 ? "0" + hour : hour;
        min = min < 10 ? "0" + min : min;
        sec = sec < 10 ? "0" + sec : sec;

        var currentTime = hour + ":" + min + ":" + sec + " " + am_pm;
        $('#clock').html(currentTime);
    }

    var date = new Date();
    $('#current-date').text(date.toLocaleDateString());
    var timezone = date.toString().split(" ")[5];
    $('#timezone').text("(" + timezone.slice(0, timezone.length - 2) + ":" + timezone.slice(-2) + ")");

    showTime();
</script></div>
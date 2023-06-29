/*========================================
js_rolling.js

#ê°„ë‹¨ì„¤ëª…
<div><img /><img /></div>
ë¼ê³  í–ˆì„ ê²½ìš° divì•ˆì˜ imgë¥¼ ìœ„,ì˜¤ë¥¸ìª½,ì•„ëž˜,ì™¼ìª½ìœ¼ë¡œ ë¡¤ë§ ì‹œí‚¨ë‹¤.




# ì‚¬ìš©ë²•
<script type="text/javascript" charset='utf-8' src="js_rolling.js"></script> 
//JSìŠ¤í¬ë¦½íŠ¸ ë¡œë“œ

<div id='div1'><img /><img /><img /><img /><img /></div>
//ì²˜ëŸ¼ êµ¬ì„±í›„ divì˜ ë„ˆë¹„ì™€ ë†’ì´ëŠ” ê¼­ ì •í•´ì£¼ê¸° ë°”ëžë‹ˆë‹¤.
<div>
<div>1<img />etc</div>
<div>2</div>
<div>3</div>
<div>4</div>
</div>
ì´ë ‡ê²Œ êµ¬ì„±í•  ê²½ìš° ë°©í–¥ì€ ìœ„,ì•„ëž˜ë¡œë§Œ ê°€ëŠ¥í•©ë‹ˆë‹¤


var roll = new js_rolling('rolling');
or
var roll = new js_rolling(document.getElementById('rolling'));
// idì´ë¦„ì„ ì ë˜ì§€, ì§ì ‘ ëŒ€ìƒì„ ì§€ëª©í•´ì„œ ë¡¤ë§ í´ëž˜ìŠ¤ë¡œ ê°ì²´ë¥¼ ë§Œë“¬

roll.set_direction(4); // ë°˜í–¥ì„ ë°”ê¿ˆ. 1: top, 2:right, 3:bottom 4:left ê·¸ì™¸ì˜ ê²½ìš° ë™ìž‘ì•ˆí•¨
roll.move_gap = 1;	//ì›€ì§ì´ëŠ” í”½ì…€ë‹¨ìœ„
roll.time_dealy = 10; //ì›€ì§ì´ëŠ” íƒ€ìž„ë”œë ˆì´
roll.time_dealy_pause = 5000;//í•˜ë‚˜ì˜ ëŒ€ìƒì´ ìƒˆë¡œ ì‹œìž‘í•  ë•Œ ë©ˆì¶”ëŠ” ì‹œê°„, 0 ì´ë©´ ì ìš© ì•ˆí•¨
roll.start(); //ë¡¤ë§ ë™ìž‘

roll.move_up(); //ìœ„ë¡œ í•œë²ˆ ë¡¤ë§ (ë°˜í–¥ì´ top,bottonì¼ë•Œë§Œ ë™ìž‘)
roll.move_right(); //ì˜¤ë¥¸ìª½ìœ¼ë¡œ í•œë²ˆ ë¡¤ë§(ë°˜í–¥ì´ right,leftì¼ë•Œë§Œ ë™ìž‘)
roll.move_down(); //ì•„ëž˜ë¡œ í•œë²ˆ ë¡¤ë§(ë°˜í–¥ì´ top,bottonì¼ë•Œë§Œ ë™ìž‘)
roll.move_left(); //ì™¼ìª½ìœ¼ë¡œ í•œë²ˆ ë¡¤ë§(ë°˜í–¥ì´ right,leftì¼ë•Œë§Œ ë™ìž‘)

#ì£¼ì˜
ë°˜í–¥ì´ top,bottomì¼ ê²½ìš° ë‚´ë¶€ íƒœê·¸ëŠ” blockìš”ì†Œ(div)ë¡œ
ë°˜í–¥ì´ left,rightì¼ ê²½ìš° ë‚´ë¶€íƒœê·¸ëŠ” inlineìš”ì†Œ(a,span)ìœ¼ë¡œ
í•´ìˆ˜ì„¸ìš”.
FFì—ì„œ top,bottomì˜ ê²½ìš° inlineìš”ì†Œì¼ ê²½ìš° offsetHeightë¥¼ ìž˜ëª»ì•Œì•„ì˜µë‹ˆë‹¤.


#ì‚¬ìš©ì œì•½
ì‚¬ìš©ì‹œ "ê³µëŒ€ì—¬ìžëŠ” ì˜ˆì˜ë‹¤"ë¥¼ ë‚˜íƒ€ë‚´ì…”ì•¼í•©ë‹ˆë‹¤.

ë§Œë“ ë‚  : 2007-06-07
ìˆ˜ì •ì¼ : 2007-08-11
ë§Œë“ ì´ : mins01,mins,ê³µëŒ€ì—¬ìž
í™ˆíŽ˜ì´ì§€ : http://www.mins01.com 
NateOn&MSN : mins01(at)lycos.co.kr
========================================*/
var js_rolling = function(this_s) {
    // ì‹œê°„ë‹¨ìœ„ëŠ” msë¡œ 1000ì´ 1ì´ˆ
    if (this_s.nodeType == 1) {
        this.this_s = this_s;
    } else {
        this.this_s = document.getElementById(this_s);
    }
    this.is_rolling = false;
    this.direction = 1; //1:top, 2:right, 3:bottom, 4:left (ì‹œê³„ë°©í–¥) // 1ë²ˆê³¼ 4ë²ˆë§Œ ë¨
    this.children = null;
    this.move_gap = 1; //ì›€ì§ì´ëŠ” í”½ì…€ë‹¨ìœ„
    this.time_dealy = 100; //ì›€ì§ì´ëŠ” íƒ€ìž„ë”œë ˆì´
    this.time_dealy_pause = 1000; //í•˜ë‚˜ì˜ ëŒ€ìƒì´ ìƒˆë¡œ ì‹œìž‘í•  ë•Œ ë©ˆì¶”ëŠ” ì‹œê°„, 0 ì´ë©´ ì ìš© ì•ˆí•¨
    this.time_timer = null;
    this.time_timer_pause = null;
    this.mouseover = false;
    this.init();
    this.set_direction(this.direction);
}
js_rolling.prototype.init = function() {
    this.this_s.style.position = 'relative';
    this.this_s.style.overflow = 'hidden';
    var children = this.this_s.childNodes;
    for (var i = (children.length - 1); 0 <= i; i--) {
        if (children[i].nodeType == 1) {
            children[i].style.position = 'relative';
        } else {
            this.this_s.removeChild(children[i]);
        }
    }
    var this_s = this;
    this.this_s.onmouseover = function() {
        this_s.mouseover = true;
        if (!this_s.time_timer_pause) {
            this_s.pause();
        }
    }
    this.this_s.onmouseout = function() {
        this_s.mouseover = false;
        if (!this_s.time_timer_pause) {
            this_s.resume();
        }
    }
}
js_rolling.prototype.set_direction = function(direction) {
    this.direction = direction;
    if (this.direction == 2 || this.direction == 4) {
        this.this_s.style.whiteSpace = 'nowrap';
    } else {
        this.this_s.style.whiteSpace = 'normal';
    }
    var children = this.this_s.childNodes;
    for (var i = (children.length - 1); 0 <= i; i--) {
        if (this.direction == 1) {
            children[i].style.display = 'block';
        } else if (this.direction == 2) {
            children[i].style.textlign = 'right';
            children[i].style.display = 'inline';
        } else if (this.direction == 3) {
            children[i].style.display = 'block';
        } else if (this.direction == 4) {
            children[i].style.display = 'inline';
        }
    }
    this.init_element_children();
}
js_rolling.prototype.init_element_children = function() {
    var children = this.this_s.childNodes;
    this.children = children;
    for (var i = (children.length - 1); 0 <= i; i--) {
        if (this.direction == 1) {
            children[i].style.top = '0px';
        } else if (this.direction == 2) {
            children[i].style.left = '-' + this.this_s.firstChild.offsetWidth + 'px';
        } else if (this.direction == 3) {
            children[i].style.top = '-' + this.this_s.firstChild.offsetHeight + 'px';
        } else if (this.direction == 4) {
            children[i].style.left = '0px';
        }
    }
}
js_rolling.prototype.act_move_up = function() {
    for (var i = 0, m = this.children.length; i < m; i++) {
        var child = this.children[i];
        child.style.top = (parseInt(child.style.top) - this.move_gap) + 'px';
    }
    if ((this.children[0].offsetHeight + parseInt(this.children[0].style.top)) <= 0) {
        this.this_s.appendChild(this.children[0]);
        this.init_element_children();
        this.pause_act();
    }
}
js_rolling.prototype.move_up = function() {
    if (this.direction != 1 && this.direction != 3) { return false; }
    this.this_s.appendChild(this.children[0]);
    this.init_element_children();
    this.pause_act();
}
js_rolling.prototype.act_move_down = function() {
    for (var i = 0, m = this.children.length; i < m; i++) {
        var child = this.children[i];
        child.style.top = (parseInt(child.style.top) + this.move_gap) + 'px';
    }
    if (parseInt(this.children[0].style.top) >= 0) {
        this.this_s.insertBefore(this.this_s.lastChild, this.this_s.firstChild);
        this.init_element_children();
        this.pause_act();
    }
}
js_rolling.prototype.move_down = function() {
    if (this.direction != 1 && this.direction != 3) { return false; }
    this.this_s.insertBefore(this.this_s.lastChild, this.this_s.firstChild);
    this.init_element_children();
    this.pause_act();
}
js_rolling.prototype.act_move_left = function() {
    for (var i = 0, m = this.children.length; i < m; i++) {
        var child = this.children[i];
        child.style.left = (parseInt(child.style.left) - this.move_gap) + 'px';
    }
    if ((this.children[0].offsetWidth + parseInt(this.children[0].style.left)) <= 0) {
        this.this_s.appendChild(this.this_s.firstChild);
        this.init_element_children();
        this.pause_act();
    }
}
js_rolling.prototype.move_left = function() {
    if (this.direction != 2 && this.direction != 4) { return false; }
    this.this_s.appendChild(this.this_s.firstChild);
    this.init_element_children();
    this.pause_act();
}
js_rolling.prototype.act_move_right = function() {
    for (var i = 0, m = this.children.length; i < m; i++) {
        var child = this.children[i];
        child.style.left = (parseInt(child.style.left) + this.move_gap) + 'px';
    }

    if (parseInt(this.this_s.lastChild.style.left) >= 0) {
        this.this_s.insertBefore(this.this_s.lastChild, this.this_s.firstChild);
        this.init_element_children();
        this.pause_act();
    }
}
js_rolling.prototype.move_right = function() {
    if (this.direction != 2 && this.direction != 4) { return false; }
    this.this_s.insertBefore(this.this_s.lastChild, this.this_s.firstChild);
    this.init_element_children();
    this.pause_act();
}
js_rolling.prototype.start = function() { //ë¡¤ë§ ì‹œìž‘
    var this_s = this;
    this.stop();
    this.is_rolling = true;
    var act = function() {
        if (this_s.is_rolling) {
            if (this_s.direction == 1) { this_s.act_move_up(); }
            else if (this_s.direction == 2) { this_s.act_move_right(); }
            else if (this_s.direction == 3) { this_s.act_move_down(); }
            else if (this_s.direction == 4) { this_s.act_move_left(); }
        }
    }
    this.time_timer = setInterval(act, this.time_dealy);
}
js_rolling.prototype.pause_act = function() { //ì¼ì‹œ ë™ìž‘
    if (this.time_dealy_pause) {
        var this_s = this;
        var act = function() { this_s.resume(); this_s.time_timer_pause = null; }
        if (this.time_timer_pause) { clearTimeout(this.time_timer_pause); }
        this.time_timer_pause = setTimeout(act, this.time_dealy_pause);
        this.pause();
    }
}
js_rolling.prototype.pause = function() { //ì¼ì‹œ ë©ˆì¶¤
    this.is_rolling = false;
}
js_rolling.prototype.resume = function() { //ì¼ì‹œ ë©ˆì¶¤ í•´ì œ
    if (!this.mouseover) {
        this.is_rolling = true;
    }
}
js_rolling.prototype.stop = function() { //ë¡¤ë§ì„ ëëƒ„
    this.is_rolling = false;
    if (!this.time_timer) {
        clearInterval(this.time_timer);
    }
    this.time_timer = null
}
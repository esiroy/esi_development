<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="My Tutor">
    <meta name="keywords" content="Tutor, Japan, Lesson">
    <title>{{ config('app.name', 'My Tutor') }}</title>
        
    <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.1/jquery.min.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link rel="preconnect" href="//fonts.gstatic.com"  crossorigin />
    <link rel="preconnect" href="//fonts.googleapis.com"  crossorigin />

    <!-- Styles -->
    <link rel="icon" href="{{ url('images/favicon.ico') }}"/>        
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    <noscript>
        <link rel="stylesheet" type="text/css" href="">
    </noscript>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Tracking Script -->
    <script>
		var url = window.location.hostname;
		//alert(url);
		if(url != 'mypage.mytutor-jpn.com' && url != 'mytutor-test.ivant.com'){
			// window.location.replace("http://mypage.mytutor-jpn.com/signup.do");
		}
	</script> 
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-1013785582"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'AW-1013785582');
	</script>
</head>
<body>

    <!--<div id="new_member_ID"> {{ $user->id }} </div> -->

    <!--<img src="https://px.a8.net/a8fly/earnings?a8={識別パラメータ}&pid=s00000012345001&so={注文番号}&si=1200-1-1200-a8&currency=JPY" width="1" height="1"  style="height: 1px;display: block;background-color:yellow">-->


	<img src="https://px.a8.net/a8fly/earnings?a8fly/earnings?a8=<<{{ app('request')->input('a8') ?? ''}}>>&pid=s00000012345001&so=<<{{ $user->id?? ''}}>>&si=1200-1-1200-a8&currency=JPY" width="1" height="1"  style="height: 1px;display: block;background-color:yellow">
    <img src="https://advack.net/m/img.php?pcode=248&aid={{ $user->id  ?? ''}}" width="1" height="1" style="height: 1px;display: block;background-color:yellow"/>     

    <!-- Google Code for &#20250;&#21729;&#30331;&#37682; Conversion Page -->
    <script type="text/javascript" defer>
        / <![CDATA[ /
        var google_conversion_id = 1013785582;
        var google_conversion_language = "en";
        var google_conversion_format = "2";
        var google_conversion_color = "ffffff";
        var google_conversion_label = "-1x3CLKOuwUQ7se04wM";
        var google_conversion_value = 1.00;
        var google_conversion_currency = "JPY";
        var google_remarketing_only = false;
        / ]]> /
    </script>
    
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"> </script>		
    <span id="a8sales" style="height: 1px;display: block;background-color:yellow"></span>

    <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt=""  src="//www.googleadservices.com/pagead/conversion/1013785582/?value=1.00&amp;currency_code=JPY&amp;label=-1x3CLKOuwUQ7se04wM&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>  
    

    <div id="app">
        
        <!--<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm"  style="background-color:#2d9bb6">-->

        <nav class="navbar navbar-expand-md navbar-light shadow-sm"  style="background-color:#2d9bb6">
            <div class="container">

            <!--
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'My Tutor') }}
                </a>

            -->

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-white font-weight-bold" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>

                            <!--
                            @if (Route::has('signup'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('signup') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                            -->
                            
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ ucfirst(Auth::user()->first_name) }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout_member') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="main-container py-4">
            @yield('content')
        </main>

        <!--
        <footer class="pt-4 pt-md-4 pb-md-4 border-top">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md text-center">
                        <a class="navbar-brand" href="{{ url( route('home') ) }}">
                            {{ config('app.name', 'My Tutor') }}
                        </a>
                    </div>
                </div>
            </div>
        </footer>
        -->

    </div>

    <script>
			/*!
			The punycode class and String.fromCodePoint method are:

			Copyright Mathias Bynens <https://mathiasbynens.be/>

			Permission is hereby granted, free of charge, to any person obtaining
			a copy of this software and associated documentation files (the
			"Software"), to deal in the Software without restriction, including
			without limitation the rights to use, copy, modify, merge, publish,
			distribute, sublicense, and/or sell copies of the Software, and to
			permit persons to whom the Software is furnished to do so, subject to
			the following conditions:

			The above copyright notice and this permission notice shall be
			included in all copies or substantial portions of the Software.

			THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
			EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
			MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
			NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
			LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
			OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
			WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
			*/
			var maxInt_a8 = 2147483647,
			    base_a8 = 36,
			    tMin_a8 = 1,
			    tMax_a8 = 26,
			    skew_a8 = 38,
			    damp_a8 = 700,
			    initialBias_a8 = 72,
			    initialN_a8 = 128,
			    delimiter_a8 = "-",
			    regexPunycode_a8 = /^xn--/,
			    regexNonASCII_a8 = /[^\0-\x7E]/,
			    regexSeparators_a8 = /[\x2E\u3002\uFF0E\uFF61]/g;
			String.fromCodePoint || function() {
			    var e = function() {
			            try {
			                var e = {},
			                    t = Object.defineProperty,
			                    r = t(e, e, e) && t
			            } catch (e) {}
			            return r
			        }(),
			        s = String.fromCharCode,
			        c = Math.floor,
			        t = function() {
			            var e, t, r = [],
			                o = -1,
			                i = arguments.length;
			            if (!i) return "";
			            for (var n = ""; ++o < i;) {
			                var a = Number(arguments[o]);
			                if (!isFinite(a) || a < 0 || 1114111 < a || c(a) != a) throw RangeError("Invalid code point: " + a);
			                a <= 65535 ? r.push(a) : (e = 55296 + ((a -= 65536) >> 10), t = a % 1024 + 56320, r.push(e, t)), (o + 1 == i || 16384 < r.length) && (n += s.apply(null, r), r.length = 0)
			            }
			            return n
			        };
			    e ? e(String, "fromCodePoint", {
			        value: t,
			        configurable: !0,
			        writable: !0
			    }) : String.fromCodePoint = t
			}();
			var errors_a8 = {
			        overflow: "Overflow: input needs wider integers to process",
			        "not-basic": "Illegal input >= 0x80 (not a basic code point)",
			        "invalid-input": "Invalid input"
			    },
			    baseMinusTMin_a8 = base_a8 - tMin_a8,
			    floor_a8 = Math.floor,
			    stringFromCharCode_a8 = String.fromCharCode;

			function error_A8(e) {
			    throw new RangeError(errors_a8[e])
			}

			function map_A8(e, t) {
			    for (var r = [], o = e.length; o--;) r[o] = t(e[o]);
			    return r
			}

			function mapDomain_A8(e, t) {
			    var r = e.split("@"),
			        o = "";
			    return 1 < r.length && (o = r[0] + "@", e = r[1]), o + map_A8((e = e.replace(regexSeparators_a8, ".")).split("."), t).join(".")
			}

			function ucs2decode_A8(e) {
			    for (var t = [], r = 0, o = e.length; r < o;) {
			        var i = e.charCodeAt(r++);
			        if (55296 <= i && i <= 56319 && r < o) {
			            var n = e.charCodeAt(r++);
			            56320 == (64512 & n) ? t.push(((1023 & i) << 10) + (1023 & n) + 65536) : (t.push(i), r--)
			        } else t.push(i)
			    }
			    return t
			}
			var ucs2encode_A8 = function(e) {
			        return String.fromCodePoint.apply(null, e)
			    },
			    basicToDigit_A8 = function(e) {
			        return e - 48 < 10 ? e - 22 : e - 65 < 26 ? e - 65 : e - 97 < 26 ? e - 97 : base_a8
			    },
			    digitToBasic_A8 = function(e, t) {
			        return e + 22 + 75 * (e < 26) - ((0 != t) << 5)
			    },
			    adapt_A8 = function(e, t, r) {
			        var o = 0;
			        for (e = r ? floor_a8(e / damp_a8) : e >> 1, e += floor_a8(e / t); baseMinusTMin_a8 * tMax_a8 >> 1 < e; o += base_a8) e = floor_a8(e / baseMinusTMin_a8);
			        return floor_a8(o + (baseMinusTMin_a8 + 1) * e / (e + skew_a8))
			    },
			    decode_A8 = function(e) {
			        var t = [],
			            r = e.length,
			            o = 0,
			            i = initialN_a8,
			            n = initialBias_a8,
			            a = e.lastIndexOf(delimiter_a8);
			        a < 0 && (a = 0);
			        for (var s = 0; s < a; ++s) 128 <= e.charCodeAt(s) && error_A8("not-basic"), t.push(e.charCodeAt(s));
			        for (var c = 0 < a ? a + 1 : 0; c < r;) {
			            for (var _ = o, u = 1, l = base_a8;; l += base_a8) {
			                r <= c && error_A8("invalid-input");
			                var d = basicToDigit_A8(e.charCodeAt(c++));
			                (base_a8 <= d || d > floor_a8((maxInt_a8 - o) / u)) && error_A8("overflow"), o += d * u;
			                var p = l <= n ? tMin_a8 : n + tMax_a8 <= l ? tMax_a8 : l - n;
			                if (d < p) break;
			                var g = base_a8 - p;
			                u > floor_a8(maxInt_a8 / g) && error_A8("overflow"), u *= g
			            }
			            var f = t.length + 1;
			            n = adapt_A8(o - _, f, 0 == _), floor_a8(o / f) > maxInt_a8 - i && error_A8("overflow"), i += floor_a8(o / f), o %= f, t.splice(o++, 0, i)
			        }
			        return String.fromCodePoint.apply(null, t)
			    },
			    encode_A8 = function(e) {
			        for (var t = [], r = (e = ucs2decode_A8(e)).length, o = initialN_a8, i = 0, n = initialBias_a8, a = 0; a < r; a++) e[a] < 128 && t.push(stringFromCharCode_a8(e[a]));
			        var s = t.length,
			            c = s;
			        for (s && t.push(delimiter_a8); c < r;) {
			            var _ = maxInt_a8;
			            for (a = 0; a < r; a++) e[a] >= o && e[a] < _ && (_ = e[a]);
			            var u = c + 1;
			            _ - o > floor_a8((maxInt_a8 - i) / u) && error_A8("overflow"), i += (_ - o) * u, o = _;
			            for (a = 0; a < r; a++)
			                if (e[a] < o && ++i > maxInt_a8 && error_A8("overflow"), e[a] == o) {
			                    for (var l = i, d = base_a8;; d += base_a8) {
			                        var p = d <= n ? tMin_a8 : n + tMax_a8 <= d ? tMax_a8 : d - n;
			                        if (l < p) break;
			                        var g = l - p,
			                            f = base_a8 - p;
			                        t.push(stringFromCharCode_a8(digitToBasic_A8(p + g % f, 0))), l = floor_a8(g / f)
			                    }
			                    t.push(stringFromCharCode_a8(digitToBasic_A8(l, 0))), n = adapt_A8(i, u, c == s), i = 0, ++c
			                }++i, ++o
			        }
			        return t.join("")
			    },
			    toUnicode_A8 = function(e) {
			        return mapDomain_A8(e, function(e) {
			            return regexPunycode_a8.test(e) ? decode_A8(e.slice(4).toLowerCase()) : e
			        })
			    },
			    toASCII_A8 = function(e) {
			        return mapDomain_A8(e, function(e) {
			            return regexNonASCII_a8.test(e) ? "xn--" + encode_A8(e) : e
			        })
			    },
			    punycode_A8 = {
			        version: "2.1.0",
			        ucs2: {
			            decode: ucs2decode_A8,
			            encode: ucs2encode_A8
			        },
			        decode: decode_A8,
			        encode: encode_A8,
			        toASCII: toASCII_A8,
			        toUnicode: toUnicode_A8
			    },
			    px_domain_a8 = function() {
			        return "//px.a8.net"
			    },
			    cookie_expires_a8 = function() {
			        return 3653
			    },
			    cookies_keep_limit_a8 = function() {
			        return 10
			    },
			    cookies_path_a8 = function() {
			        return "/"
			    },
			    stoplog_a8 = function() {
			        for (var e = document.getElementsByTagName("script"), t = 0; t < e.length; t++) {
			            var r = e[t].getAttribute("data-a8stoplog");
			            if (r) return r
			        }
			    };
			String.prototype.trim = function() {
			    return this.replace(/^\s+|\s+$/g, "")
			};
			var getUTCtime_A8 = function(e) {
			        var t = new Date;
			        return t.setDate(t.getDate() + e), t.toUTCString()
			    },
			    logPrinter_A8 = function(e, t) {
			        !stoplog_a8() && window.console && "function" == typeof window.console.log && console.log("[" + t + "] " + e)
			    };

			function checkCurrency_A8(e) {
			    return "string" == typeof e && !0 === /^[A-Za-z]/.test(e) && 3 == e.length ? e : (logPrinter_A8("currency = " + e + ": \u901a\u8ca8\u5358\u4f4d\u6307\u5b9a\u304c\u5b58\u5728\u3057\u306a\u3044\u304b\u3001\u6b63\u3057\u304f\u8a8d\u8b58\u3055\u308c\u306a\u304b\u3063\u305f\u305f\u3081\u300cJPY\u300d\u3092\u9069\u7528\u3057\u307e\u3059\u3002", "DEBUG"), "JPY")
			}

			function genarateSIparam_A8(e) {
			    if ("number" != typeof e.price) return "";
			    var t, r, o = e.price;
			    return t = "number" == typeof e.quantity && 0 <= e.quantity && !1 === /^-?[0-9]+\.[0-9]+$/.test(e.quantity) ? Math.floor(e.quantity) : 1, r = "string" == typeof e.code && "" !== e.code ? e.code : "a8", "&si=" + o + "-" + t + "-" + ("number" == typeof e.total_price && "" !== e.total_price ? e.total_price : o * t) + "-" + r
			}
			var docCookies_A8 = {
					
			        getItem: function(e) {
			            return e && this.hasItem(e) ? unescape(document.cookie.replace(new RegExp("(?:^|.*;\\s*)" + escape(e).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*"), "$1")) : null
			        },
			        setItem: function(e, t, r, o, i, n) {
			        	if (e && !/^(?:expires|max\-age|path|domain|secure)$/i.test(e)) {
			                var a = "";
			                if (r) switch (r.constructor) {
			                    case Number:
			                        a = r === 1 / 0 ? "; expires=Tue, 19 Jan 2038 03:14:07 GMT" : "; max-age=" + r;
			                        break;
			                    case String:
			                        a = "; expires=" + r;
			                        break;
			                    case Date:
			                        a = "; expires=" + r.toGMTString()
			                }
			                document.cookie = escape(e) + "=" + escape(t) + a + (i ? "; domain=" + i : "") + (o ? "; path=" + o : "") + (n ? "; secure" : "")
			            }
			        },
			        removeItem: function(e, t, r) {
			        	if (e && this.hasItem(e)) return document.cookie = escape(e) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (t ? "; path=" + t : "") + (r ? "; domain=" + r : ""), this.hasItem(e)
			        },
			        hasItem: function(e) {
			        	 return new RegExp("(?:^|;\\s*)" + escape(e).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=").test(document.cookie)
			        },
			        hasLikeItem: function(e) {
			        	return new RegExp("(?:^|;\\s*\\w*)" + escape(e).replace(/[\-\.\+\*]/g, "\\$&") + "\\w*\\s*\\=").test(document.cookie)
			        },
			        keys: function() {
			            for (var e = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/), t = 0; t < e.length; t++) e[t] = unescape(e[t]);
			            return e
			        },
			        keysPickup: function(e) {
			            for (var t = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/), r = [], o = 0, i = 0; i < t.length; i++) 0 <= unescape(t[i].indexOf(e)) && (r[o++] = unescape(t[i]));
			            return r
			        }
			        
			        
			    },
			    delCookie_A8 = function(e, t) {
			        return !docCookies_A8.removeItem(e, cookies_path_a8()) || !docCookies_A8.removeItem(e, cookies_path_a8(), t)
			    },
			    callDelCookie_A8 = function(e, t) {
			        for (var r = punycode_A8.toASCII(location.hostname).split("."), o = "", i = r.length - 1; 0 <= i; i--)
			            if (o = "." + r[i] + o, delCookie_A8(e, o)) return !0;
			        return !1
			    },
			    getCookie_A8 = function(e, t, r) {
			    	
			        var o = e;
			        if (t && (o += "_" + t), docCookies_A8.hasItem(o)) return r ? docCookies_A8.getItem(o) : o;
			        if (docCookies_A8.hasLikeItem(o)) {
			            var i = docCookies_A8.keysPickup(o);
			            return r ? docCookies_A8.getItem(i[0]) : i[0]
			        }
			        return docCookies_A8.hasItem(e) ? r ? docCookies_A8.getItem(e) : e : void 0
			    },
			    getParamValue_A8 = function(e) {
			        var t = window.location.search.split("?");
			        if (1 < t.length)
			            for (var r = t[1].split("&"), o = 0; o < r.length; o++) {
			            	
			                var i = r[o].split("=");
			                if (i[0] === e) return i[1]
			            }
			        return logPrinter_A8("there is not a8 parameter", "INFO"), ""
			    },
			    getPid_A8 = function(e) {
			        var t = e.slice(-15);
			        return /s\d{14}/.test(t) ? t : ""
			    },
			    cookieSetting_A8 = function(e, t) {
			        if (!t || !e) {
			        	return logPrinter_A8("url does not have a8 parameter", "DEBUG"), !1;
			        
			        }
			        if (!getPid_A8(t)) return logPrinter_A8("a8 parameter does not have pid!", "DEBUG"), !1;
			        var r = docCookies_A8.keysPickup(e);
			        if (r.length >= cookies_keep_limit_a8()) {
			            var o = r[0];
			            logPrinter_A8("limit over. most old cookie is =" + o + "=" + docCookies_A8.getItem(o), "DEBUG")
			        }
			        for (var i = 0 ; i<2 ; i++){
			        }
			        for (var i = e + "_" + getPid_A8(t), n = punycode_A8.toASCII(location.hostname).split("."), a = "", s = n.length; 0 < s; s--)
			            if (a = "" === a ? n[s - 1] : n[s - 1] + a, 1 < s && (a = "." + a), o && delCookie_A8(o, a) && logPrinter_A8("delete old cookie (" + o + "; domain=" + a + ")", "DEBUG"), docCookies_A8.setItem(i, t, getUTCtime_A8(cookie_expires_a8()), cookies_path_a8(), a), docCookies_A8.hasItem(i) && docCookies_A8.getItem(i) === t) return logPrinter_A8("set cookie (" + i + "=" + t + "; domain=" + a + ")", "DEBUG"), !0;
			        return ""
			    };

			function a8sales(e) {
				   if ("" === "${a8}"){
					   console.log('a8 was not set');
				   }else{
					   console.log('a8 OK');
			    logPrinter_A8("a8sales() start", "INFO");
			    var t = document.getElementById("a8sales");
			    if (t) {
			        var r = "",
			            o = px_domain_a8();
			        "string" == typeof e.pid && 15 == e.pid.length ? r = o + "/a8fly/earnings?a8=${a8}&pid=" + e.pid : "string" == typeof e.eid && 12 == e.eid.length ? r = "string" == typeof e.eno && 2 == e.eno.length ? o + "/a8fly/earnings?eid=" + e.eid + "&eno=" + e.eno : o + "/a8fly/earnings?eid=" + e.eid : e.pid ? "string" != typeof e.pid ? logPrinter_A8(e.pid + ": pid\u304c\u6587\u5b57\u5217\u3067\u306f\u3042\u308a\u307e\u305b\u3093\u3002", "ERROR") : 15 != e.pid.length && logPrinter_A8(e.pid + ": pid\u306e\u6841\u6570\u304c\u7570\u306a\u308a\u307e\u3059\u3002", "ERROR") : e.eid ? "string" != typeof e.eid ? logPrinter_A8(e.eid + ": eid\u304c\u6587\u5b57\u5217\u3067\u306f\u3042\u308a\u307e\u305b\u3093\u3002", "ERROR") : 12 != e.eid.length && logPrinter_A8(e.eid + ": eid\u306e\u6841\u6570\u304c\u7570\u306a\u308a\u307e\u3059", "ERROR") : logPrinter_A8("pid\u53ca\u3073eid\u304c\u8a2d\u5b9a\u3055\u308c\u3066\u304a\u308a\u307e\u305b\u3093\u3002", "ERROR");
			        var i = "";
			        if (void 0 === e.items) {
			            if ("number" != typeof e.price || "number" != typeof e.quantity) return "number" != typeof e.price && logPrinter_A8("price = " + e.price + ": \u5546\u54c1\u5358\u4fa1\u306e\u5165\u529b\u5024\u304c\u7570\u306a\u308a\u307e\u3059\u3002\u534a\u89d2\u6570\u5b57\u3067\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044\u3002", "ERROR"), "number" != typeof e.quantity && logPrinter_A8("quantity = " + e.quantity + ": \u5546\u54c1\u500b\u6570\u306e\u5165\u529b\u5024\u304c\u7570\u306a\u308a\u307e\u3059\u3002\u534a\u89d2\u6570\u5b57\u3067\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044\u3002", "ERROR"), void logPrinter_A8("\u6b63\u5e38\u306a\u5024\u304c\u53d6\u5f97\u3067\u304d\u306a\u3044\u305f\u3081\u3001\u51e6\u7406\u3092\u7d42\u4e86\u3057\u307e\u3059\u3002", "INFO");
			            i = genarateSIparam_A8({
			                code: e.code,
			                price: e.price,
			                quantity: e.quantity,
			                total_price: e.total_price
			            })
			        } else
			            for (var n = 0; n < e.items.length; n++) {
			                if ("number" != typeof e.items[n].price || "number" != typeof e.items[n].quantity) return "number" != typeof e.items[n].price && logPrinter_A8("price = " + e.items[n].price + ": \u5546\u54c1\u5358\u4fa1\u306e\u5165\u529b\u5024\u304c\u7570\u306a\u308a\u307e\u3059\u3002\u534a\u89d2\u6570\u5b57\u3067\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044\u3002", "ERROR"), "number" != typeof e.items[n].quantity && logPrinter_A8("quantity = " + e.items[n].quantity + ": \u5546\u54c1\u500b\u6570\u306e\u5165\u529b\u5024\u304c\u7570\u306a\u308a\u307e\u3059\u3002\u534a\u89d2\u6570\u5b57\u3067\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044\u3002", "ERROR"), void logPrinter_A8("\u6b63\u5e38\u306a\u5024\u304c\u53d6\u5f97\u3067\u304d\u306a\u3044\u305f\u3081\u3001\u51e6\u7406\u3092\u7d42\u4e86\u3057\u307e\u3059\u3002", "INFO");
			                i += genarateSIparam_A8(e.items[n])
			            }
			        var a = checkCurrency_A8(e.currency),
			            s = getCookie_A8("_a8", e.pid ? e.pid : e.eid, "value"),
			    
			            c = getCookie_A8("_a8", e.pid ? e.pid : e.eid);
			        if (!i) return logPrinter_A8("si = " + i + ": si\u5024\u306b\u6b63\u3057\u3044\u5024\u304c\u5165\u529b\u3067\u304d\u3066\u304a\u308a\u307e\u305b\u3093\u3002a8sales\u306eitems\u306e\u78ba\u8a8d\u3092\u3057\u3066\u304f\u3060\u3055\u3044\u3002", "ERROR"), void logPrinter_A8("\u6b63\u5e38\u306a\u5024\u304c\u53d6\u5f97\u3067\u304d\u306a\u3044\u305f\u3081\u3001\u51e6\u7406\u3092\u7d42\u4e86\u3057\u307e\u3059\u3002", "INFO");
			        if (!r) return logPrinter_A8("\u6210\u679c\u30bf\u30b0\u306eURL\u306e\u751f\u6210\u306b\u5931\u6557\u3057\u307e\u3057\u305f\u3002", "ERROR"), void logPrinter_A8("\u6b63\u5e38\u306a\u5024\u304c\u53d6\u5f97\u3067\u304d\u306a\u3044\u305f\u3081\u3001\u51e6\u7406\u3092\u7d42\u4e86\u3057\u307e\u3059\u3002\u8a2d\u5b9a\u5024\u3092\u78ba\u8a8d\u3057\u3066\u304f\u3060\u3055\u3044\u3002", "INFO");
			        var _ = "string" == typeof e.order_number && "" !== e.order_number ? e.order_number.trim() : "";
			        if ("string" == typeof e.order_number && "" !== e.order_number && "" !== _) var u = e.order_number;
			        else {
			            var l = new Date;
			            u = "null-" + ("" + l.getFullYear() + (l.getMonth() + 1) + (l.getDate() < 10 ? "0" + l.getDate() : l.getDate()) + (l.getHours() < 10 ? "0" + l.getHours() : l.getHours()) + (l.getMinutes() < 10 ? "0" + l.getMinutes() : l.getMinutes()) + (l.getSeconds() < 10 ? "0" + l.getSeconds() : l.getSeconds())) + "-" + ("0000" + Math.floor(1e4 * Math.random())).slice(-4);
			            e.order_number_custom = u, logPrinter_A8("\u6ce8\u6587\u756a\u53f7\u304c\u8a2d\u5b9a\u3055\u308c\u3066\u3044\u307e\u305b\u3093\u3002null-\u30bf\u30a4\u30e0\u30b9\u30bf\u30f3\u30d7-\u4e71\u6570\u3092\u81ea\u52d5\u751f\u6210\u3057\u307e\u3059\u3002", "DEBUG"), logPrinter_A8("order_number = " + u, "DEBUG")
			        }
			        if (void 0 === s) var d = r + "&so=" + u  + "&si=1-1-1-a8&currency=" + a + "&type=image";
			        else d = r + "&so=" + u  + "&si=926-1-926-a8&currency=" + a + "&type=image";
			        e.si = i;
			        var p = document.createElement("img");
			        p.setAttribute("src", d), p.setAttribute("width", "100"), p.setAttribute("height", "100"), p.setAttribute("border", "10"), t.appendChild(p), "undefined" !== d && logPrinter_A8("a8sales() is SUCCESS. URL=" + d, "DEBUG"), logPrinter_A8("remove cookie -> " + c + "=" + s, "DEBUG"), logPrinter_A8("Is not there " + c + " cookie ? : " + callDelCookie_A8(c, s), "DEBUG"), logPrinter_A8("a8sales() end", "INFO")
			        var a8Param = e.a8Param;
			        
			        var htmlBlock = '<img src="'+d+'"/>';
			  	  $("#a8sales").html(htmlBlock);
			  	
			    } else logPrinter_A8("span\u30bf\u30b0\u304c\u5b58\u5728\u3057\u307e\u305b\u3093\u3002\u51e6\u7406\u3092\u7d42\u4e86\u3057\u307e\u3059\u3002", "ERROR")
				
			  }
			
			}
			getParamValue_A8("a8") && logPrinter_A8("Set cookie :" + cookieSetting_A8("_a8", getParamValue_A8("a8")), "INFO");
			</script>

    <script>
        console.log("member activated");
    </script>
    @yield('scripts')
</body>
</html>

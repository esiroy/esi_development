

Course {{ $writingGrade['course'] }} {{PHP_EOL}}
Material {{ $writingGrade['material'] }} {{PHP_EOL}}
Subject {{ $writingGrade['subject'] }} {{PHP_EOL}}
Appointed {{ boolval($writingGrade['appointed'])  }} {{PHP_EOL}}

Grade {{ $writingGrade['grade'] }} {{PHP_EOL}}
Words {{ $writingGrade['words'] }} {{PHP_EOL}}
Content {{ $writingGrade['content'] }} {{PHP_EOL}}
grade {{ $writingGrade['grade'] }} {{PHP_EOL}}


Attachment {{ url($writingGrade['attachment']) }} {{PHP_EOL}}


Link : <a download href="{{ url($writingGrade['attachment']) }}">{{ basename($writingGrade['attachment']) }}</a> {{PHP_EOL}}

#compdef aws-upload

function __aws-upload() {
    local curcontext="$curcontext" state line
    typeset -A opt_args
	 
    _arguments \
           '1: :->project'\
           '2: :->env'
    _projects=(${$(aws-upload -p)}) 
    
    case $state in
         project)
             compadd "$@" $_projects
         ;;
         env)
             _envs=(${$(aws-upload -e $words[2]):t})
             compadd "$@" $_envs
         ;;
    esac
}

compdef __aws-upload aws-upload




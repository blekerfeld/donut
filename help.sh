# Loop over all the commits and use the --commit-filter
# to change only the email addresses

git filter-branch --commit-filter '

    # check to see if the committer (email is the desired one)
    # Set the new desired name
    GIT_COMMITTER_NAME="Emma de Roo";
    GIT_AUTHOR_NAME="Emma de Roo";

    # Set the new desired email
    GIT_COMMITTER_EMAIL="code@emmalynn.xyz";
    GIT_AUTHOR_EMAIL="code@emmalynn.xyz";

    # (re) commit with the updated information
    git commit-tree "$@";' 
HEAD
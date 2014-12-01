<!DOCTYPE html>
<html>
  <head>
    <title>Git Integration</title>
    <link rel="stylesheet" href="/css/style.css" />
    <script type="application/javascript" src="/js/script.js"></script>
  </head>
  <body>
    <h1><code>git</code> Integration</h1>
    <h2>Contents</h2>
    <ul>
      <li><a href="#intro">Introduction</a></li>
      <li><a href="#trunksbranches">Trunks and Branches</a></li>
      <li><a href="#workflow">Workflow</a>
        <ul>
          <li><a href="#getstarted" >Getting Started</a></li>
          <li><a href="#developing" >Developing</a></li>
          <li><a href="#releasing" >Releasing</a></li>
          <li><a href="#maintaining" >Maintaining</a></li>
          <li><a href="#recovering" >Recovering</a></li>
        </ul>
      </li>
    </ul>
    <h2 id="intro">Introduction</h2>
    <p>
      When developing software or a website, version control is necessary for developer collaboration and to keep track of changes in code, in case a change needs to be reviewed or reverted.
      There are several programs that can be used to accomplish this goal, one of which is <code>git</code>.
      This document will discuss a recommended way to use <code>git</code> for not only version control and collaboration, but also for site database backups.
      When using git in this manner, it is recommended to either control your own remote git server or to use the <em>private</em> setting for a hosted git server.
      This would ensure that sensitive information such as the content of a website's database dump and database username and password settings are not exposed to the world.
    </p>
    <h2 id="trunksbranches">Trunks and Branches</h2>
    <p>
      When tracking code for a project, it is best to manage it with several trunks, each roughly matching a target server for the code.
      Usually, there would be two trunks:
    </p>
    <ul>
      <li><code>master</code>: This branch must <strong>always</strong> reflect what is on the production site's server. It will also track the server's database.</li>
      <li><code>develop</code>: This branch would <em>usually</em> be located on the development server. The purpose is to test the recently added features.</li>
    </ul>
    <p>
      In addition to these trunks, there are various types of branches that would assist developers in managing the code.
      These branches have a prefix that designates the type of branch, then a hyphen, then a description that explains what the branch is about.
      It is best to only use lowercase letters, numbers, underscores, and periods for the branch name.
      Avoid slashes as they can cause confusion when dealing with remote branches as the slash is a delimiter between the remote and branch name.
      Spaces are illegal in git as spaces can cause issues on the command line.
      The branches that are used are described below:
    </p>
    <ul>
      <li><code>release-*</code>: This branch reflects a pending release and would <em>usually</em> reside on a staging server if you have a three-server setup. The purpose is to use this branch for final bugfixes prior to release.</li>
      <li><code>hotfix-*</code>: This branch is used for bugfixes or other "emergency" development work. This <em>usually</em> resides on developer's individual machines.</li>
      <li><code>feature-*</code>: This branch is used for new features or refactoring related development work. This also <em>usually</em> resides on developer's individual machines.</li>      
    </ul>
    <h2 id="workflow">Workflow</h2>
    <p>
      The workflow combined with the branches provides a <code>git</code> integration that reduces downtime on the production server, provides a means for disaster recovery, and improves developer collaboration.
      This workflow uses the <a href="https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow">Gitflow Workflow</a> as described on the Atlassian website.
      This workflow keeps the <code>develop</code> and <code>master</code> branches separate to minimize the number of merges into the production server and to keep the production server stable.
      All development typically occurs on the developers' individual machines and the development and staging servers are reserved for testing the compatibility of features to the whole site.
      The next sections describe in detail how this workflow would work for both development and backup purposes.
    </p>
    <h3 id="getstarted">Getting Started</h3>
    <p>
      Hopefully, getting started occurs in an empty directory, but sometimes an initial <code>git commit</code> occurs on an already running project.
      Following agile principles, the development team should release early and often, so that the client may be involved early and often in the process.
      As a result, building the production server and its stack should be the first step in the process.
      It is recommended to have a separate staging and development server in addition to the production server.
      The development server would be used to track the <code>develop</code> branch while the staging server would follow the latest <code>release-*</code> branch.
      If the two servers are separate (virtual) machines, it would permit the same database settings to be used for both servers.
      Add a few files, most likely either the stock CMS of choice such as Drupal or in the case of a custom site the typical <code>includes</code> files that are usually used.
      Commit these files, then create the <code>develop</code> branch and push to the remote git repository.
      This is the first and last time that the <code>master</code> and <code>develop</code> branches exist on the same commit.
      The diagram below shows an example of how the Gitflow Workflow works.
    </p>
<?php echo file_get_contents('./svg/drawing.svg'); ?>
    <h3 id="developing">Developing</h3>
    <p>
      Immediately after committing the base files that are included in the project, create a <code>develop</code> branch and commit any necessary changes to match the development server's environment.
      After that, developers can begin branching off their feature branches in preparation for the next release.
      These branches are named <code>feature-description</code> where the <code>description</code> is short description of the feature being developed.
      For example <code>feature-taxonomy_map</code> would be a feature that adds a taxonomy vocabulary in Drupal related to a United States map.
      Each developer maintains the feature that he or she is working on, and regularly pushes the feature branch to the git server.
      By pushing feature branches to the git server, this allows other developers to assist the original developer without needing access to the local machine.
      This also helps if a developer is pulled onto another project and another developer needs to pick up where the other one left off.
      Once the feature is deemed ready to be tested, it will be merged into <code>develop</code>.
      Occasionally, those who don't mind using <code>vim</code> or <code>nano</code> may make changes directly on the development server, which would be tracked in the <code>develop</code> branch and pushed.
    </p>
    <h3 id="releasing">Releasing</h3>
    <p>
      This is done on the local machine as there may be merge conflicts that need to be resolved before pushing to the git server.
      At release time, the current status of the <code>develop</code> branch is forked into a <code>release</code> branch.
      The form for naming a release branch is <code>release-x.y.z</code> where <code>x</code> is the major version number, <code>y</code> is the minor release number, and <code>z</code> is the revision number if only part of a minor release is being staged.
      Any feature branches that have not been merged into <code>develop</code> would have to wait until the next release cycle, which does not necessarily need to be a fixed time period.
      While other developers may be working on features for the next release, the <code>release-x.y.z</code> is being tested and updated on a staging server.
      The developers work on their local machines by checking out the branch, making changes, then pushing the code back to the git server.
      Once the release is deemed to be sent to the production server, the developer would check out <code>master</code> on his or her <em>local</em> machine and merge in the <code>release-x.y.z</code> branch.
      Any conflicts would be resolved locally, keeping in mind if a setting file is different on the production server, to <code>git checkout origin/master -- filename</code> then committing that result.
      This is very important is the development and staging servers have different database settings than the live site.
      However, it is recommended to use the same settings to prevent this issue from occurring.
      The final step is to log onto the production server directly via <code>ssh</code> and going to the webroot to fetch the latest git repository.
      The developer would merge with <code>origin/master</code>, which should simply fast-forward to a later commit as all conflicts were previously handled locally.
      When it appears that everything is good, use a git tag to describe the release by its version number, preceded by a "v".
      Make sure to <code>git push --tags</code> so that the team can see the tag.
      Once this is completed, the developer must then check out <code>develop</code>, merge, then address any conflicts.
      This branch would be pushed, then fetched on the development server and fast-forwarded to update that server with any changes that were made to make the release ready for release.
      Except for the initial commit, <code>master</code> or <code>develop</code> never meet again and either should not be merged into the other.
      Cherry-picking is acceptable though, especially when a fix may be a hotfix that ended being done on development.
    </p>
    <h3 id="maintaining">Maintaining</h3>
    <p>
      Between release cycles, it is likely that unexpected behavior may occur and need immediate developer attention.
      This is where <code>hotfix-*</code> branches are used.
      These branches are forked directly off of <code>master</code>, then worked on locally.
      Once the hotfix is merged into <code>master</code> and <code>develop</code>, all the branches are pushed to the git server, then fetched and merged to their respective servers.
      Similar to the development server, those who don't mind using <code>vim</code> or <code>nano</code> may make changes directly on the deployment server, which would be tracked in the <code>master</code> branch and pushed.
      It really is not recommended unless the developer knows the server well and its command line tools <em>and</em> updating the code directly on the server is deemed the best option in that situation.
      It is important to commit any changes and if something goes wrong, <code>git reset</code> may help serve as an undo.
      There are times that a <code>git reset --hard</code> may be necessary, but understand the implications of potentially losing any commits that were made after the commit being used as the reset.
      It is recommended to have a nightly <code>cron</code> job that would dump the production database, then commits it to the <code>master</code> branch.
      On unlikely event that the branch is further along in the git server (it should not because it should be tracking the production server), it is not recommended for the automated job to push the branch.
      Developers should occasionally push the <code>master</code> branch to the git repository to the git server.
      Not only are these data dumps used for disaster recovery, it is useful to occasionally update the development and staging databases with the latest data dump.
      This can be accomplished by logging onto the development or staging server to fetch the git repository, then run <code>git checkout origin/master -- path/to/datadump.sql</code>, then committing the result.
      Databases are <em>never</em> imported into the production server unless it is due to a disaster recovery operation.
      In addition, in a <code>.gitignore</code>ed directory, a weekly job can be run to package the uploaded files into a <code>tar.gz</code> archive that would be fetched by a developer during the week before the next weekly run.
      This may be done in addition to virtual machine backups as an added measure of redundancy as well as provides an easy access to a backup file that can be transferred to the staging or development servers.
    </p>
    <h3 id="recovering">Recovering</h3>
    <p>
      While this workflow is very robust and the developers strive to avoid a situation where a disaster happens, it is best to be prepared and test for unexpected issues.
      If the production server is not yet actually providing a production site, it can be used for testing recovery from disasters such as sudden loss of the site.
      Another method is to provide a "fire drill" exercise where the developers need to build a new virtual machine from only git repository and the files archives.
      The measure of success would be how much time it takes, how well the site matches the live site.
      It should match the site nearly identically functionally, the database should be no more than a day or two stale, and have as much as a week or two worth of uploads missing.
    </p>
  </body>
</html>

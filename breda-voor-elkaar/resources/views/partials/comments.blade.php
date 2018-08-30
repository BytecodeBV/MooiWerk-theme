@php
if (post_password_required()) {
  return;
}
@endphp

<section id="comments" class="comments mt-3">
  @if (have_comments())
    <h2>
      {!! sprintf(_nx('One response to &ldquo;%2$s&rdquo;', '%1$s responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'sage'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>') !!}
    </h2>

    <ol class="medias mt-5 px-sm-0 mx-sm-0">
      {!! wp_list_comments([
        'style'         => 'ol',
        'max_depth'     => 4,
        'short_ping'    => true,
        'avatar_size'   => '50',
        'walker'        => new Bootstrap_Comment_Walker()
      ]) !!}
    </ol>

    @if (get_comment_pages_count() > 1 && get_option('page_comments'))
      <nav>
        <ul class="pager">
          @if (get_previous_comments_link())
            <li class="previous">@php previous_comments_link(__('&larr; Older comments', 'sage')) @endphp</li>
          @endif
          @if (get_next_comments_link())
            <li class="next">@php next_comments_link(__('Newer comments &rarr;', 'sage')) @endphp</li>
          @endif
        </ul>
      </nav>
    @endif
  @endif

  @if (!comments_open() && get_comments_number() != '0' && post_type_supports(get_post_type(), 'comments'))
    <div class="alert alert-warning">
      {{ __('Reacties zijn gesloten.', 'sage') }}
    </div>
  @endif

  @php //comment_form()
    $commenter = wp_get_current_commenter();
	$req = true;
	$aria_req = ( $req ? " aria-required='true'" : '' );
    $fields =  array(
        'author' =>
        '<p class="comment-form-author"><div class="form-group"><label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
        ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
        '" ' . $aria_req . ' /></div></p>',

        'email' =>
        '<p class="comment-form-email"><div class="form-group"><label for="email">' . __( 'Email', 'domainreference' ) . '</label> ' .
        ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="email" name="email" class="form-control" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" ' . $aria_req . ' /></div></p>',

        'url' =>
        '<p class="comment-form-url"><div class="form-group"><label for="url">' . __( 'Website', 'domainreference' ) . '</label>' .
        '<input id="url" name="url" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
        '"   /></p>',
    );
    $comments_args = array(
        // change "Leave a Reply" to "Comment"
        'title_reply'=>'Discuss this post ?',
        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        'comment_field' =>  '<p class="comment-form-comment"><div class="form-group"><label for="comment">' . _x( 'Comment', 'noun' ) .
        '</label><textarea id="comment" name="comment" class="form-control"  cols="30" rows="4" aria-required="true">' .
        '</textarea></div></p>',
        'comment_notes_after' => ' ',
        'class_submit' => 'btn btn-block'
    ); 
    comment_form($comments_args);
  @endphp
</section>

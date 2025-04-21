import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/features/news/blocs/news_bloc.dart';
import 'package:calamitech/features/news/presentation/news_card.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class NewsCards extends StatefulWidget {
  const NewsCards({super.key});

  @override
  State<NewsCards> createState() => _NewsCardsState();
}

class _NewsCardsState extends State<NewsCards> {
  @override
  void initState() {
    super.initState();
    if (!mounted) return;
    context.read<NewsBloc>().add(NewsFetched());
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<NewsBloc, NewsState>(
      buildWhen: (context, state) =>
          state is NewsLoading || state is NewsLoaded || state is NewsFailure,
      builder: (context, state) {
        if (state is NewsLoading) {
          return SizedBox(
            height: 250,
            child: Center(
              child: CircularProgressIndicator(
                color: AppTheme.primaryColor,
              ),
            ),
          );
        }

        if (state is NewsLoaded) {
          return SizedBox(
            height: 250,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: state.newsList.length,
              itemBuilder: (context, index) {
                return NewsCard(
                  news: state.newsList[index],
                );
              },
            ),
          );
        }

        if (state is NewsFailure) {
          return Center(
            child: Text(state.message),
          );
        }

        return const SizedBox.shrink();
      },
    );
  }
}

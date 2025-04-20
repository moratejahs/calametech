// import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
// import 'package:calamitech/features/news/blocs/news_bloc.dart';
// import 'package:calamitech/features/news/views/news_card.dart';
// import 'package:flutter/material.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';
//
// class NewsCards extends StatefulWidget {
//   const NewsCards({super.key});
//
//   @override
//   State<NewsCards> createState() => _NewsCardsState();
// }
//
// class _NewsCardsState extends State<NewsCards> {
//   @override
//   void initState() {
//     final loginBloc = context.read<LoginBloc>();
//     final loginBlocState = loginBloc.state;
//     final token = loginBlocState is LoginSuccess ? loginBlocState.user.token : '';
//     context.read<NewsBloc>().add(NewsFetched(token: token));
//     super.initState();
//   }
//
//   @override
//   Widget build(BuildContext context) {
//     return SizedBox(
//       height: 250,
//       child: BlocBuilder<NewsBloc, NewsState>(
//         builder: (context, state) {
//           if (state is NewsLoading) {
//             return const Center(
//               child: CircularProgressIndicator(),
//             );
//           }
//
//           if (state is NewsLoaded) {
//             return SizedBox(
//               height: 300,
//               child: ListView.builder(
//                 scrollDirection: Axis.horizontal,
//                 itemCount: state.newsList.length,
//                 itemBuilder: (context, index) {
//                   return NewsCard(
//                     news: state.newsList[index],
//
//                   );
//                 },
//               ),
//             );
//           }
//
//           if (state is NewsFailure) {
//             return Center(
//               child: Text(state.message),
//             );
//           }
//
//           return const SizedBox();
//         },
//       ),
//     );
//   }
// }

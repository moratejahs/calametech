import 'package:calamitech/core/location/cubit/location_cubit.dart';
import 'package:calamitech/features/home/view/calamity_tips.dart';
import 'package:calamitech/features/home/view/report_form.dart';
import 'package:calamitech/features/news/views/news_cards.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    context.read<LocationCubit>().startLocationUpdates();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return const Padding(
      padding: EdgeInsets.all(16.0),
      child: SingleChildScrollView(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            NewsCards(),
            CalamityTips(),
            ReportForm(),
          ],
        ),
      ),
    );
  }
}

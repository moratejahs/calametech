import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/features/ai_tips/bloc/tips_bloc.dart';
import 'package:calamitech/features/ai_tips/widgets/tip_card.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class TipsScreen extends StatefulWidget {
  final String? tipType;
  const TipsScreen({super.key, this.tipType});

  @override
  State<TipsScreen> createState() => _TipsScreenState();
}

class _TipsScreenState extends State<TipsScreen> {
  @override
  void initState() {
    context.read<TipsBloc>().add(TipsFetched());
    super.initState();
  }

  final tipTypes = {
    'safety_tips': 'Safety Tips',
    'fire_tips': 'Fire Tips',
    'flood_tips': 'Flood Tips',
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        backgroundColor: AppTheme.primaryColor,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () => context.go(RouteConstants.home),
        ),
        title: Text(widget.tipType != null ? tipTypes[widget.tipType] ?? 'Tips' : 'Tips', style: const TextStyle(color: Colors.white)),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: BlocBuilder<TipsBloc, TipsState>(
            builder: (context, state) {
              if (state is TipsLoading) {
                return const Center(
                  child: CircularProgressIndicator(),
                );
              }

              if (state is TipsLoaded) {
                final tips = state.tips;
                final tipsToShow = widget.tipType != null ? tips.where((tip) => tip.type == widget.tipType).toList() : tips;

                return tipsToShow.isEmpty
                    ? const Center(
                        child: Text('No tips available'),
                      )
                    : ListView.builder(
                        scrollDirection: Axis.vertical,
                        itemCount: tipsToShow.length,
                        itemBuilder: (context, index) {
                          return Container(
                            margin: const EdgeInsets.only(bottom: 12),
                            width: double.infinity,
                            child: TipCard(index: index + 1, tip: tipsToShow[index]),
                          );
                        },
                      );
              }

              if (state is TipsError) {
                return Center(
                  child: Text(state.message),
                );
              }

              return const SizedBox.shrink();
            },
          ),
        ),
      ),
    );
  }
}
